<?php
/*
$eventIDs = [46860, 46861];
# $eventIDs = [46368, 46369, 46370];

$dirtEvent = new dirtEvent();

foreach($eventIDs AS $eventID) {
    echo $eventID.' : ';
    var_dump($dirtEvent->getEvent($eventID));
}
*/

namespace App\Services\DirtRally;

use App\Jobs\DirtRally\ImportEventJob;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtStage;
use App\Models\DirtRally\DirtStageInfo;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ImportDirt extends ImportAbstract
{
    use DispatchesJobs;

    /**
     * Add jobs for any events that need an import
     */
    public function queueEventJobs()
    {
        foreach(DirtEvent::with('stages.results')->get() as $event) {
            // only start checking events after they open
            // only check events if they're not marked as complete
            if ($event->racenet_event_id
                && ($event->opens < Carbon::now())
                && (!$event->last_import || $event->last_import->lte($event->closes))) {
                $this->dispatch(new ImportEventJob($event));
            }
        }
    }

    /**
     * Each event should have a final import 5 minutes before it closes
     * (just in case we can't get the results after this time)
     * and an import one minute after it closes
     * This gets hit every minute (ouch) to see if this is true for any current event.
     */
    public function queueImports()
    {
        foreach(DirtEvent::with('stages.results')->get() as $event) {
            $now = Carbon::now();
            if (
                $now->between(
                    $event->closes->copy()->subMinutes(5),
                    $event->closes->copy()->subMinutes(4)->addSecond(),
                    true)
                ||
                $now->between(
                    $event->closes->copy()->addMinutes(1),
                    $event->closes->copy()->addMinutes(2)->subSecond(),
                    true)
            ) {
                $this->dispatch(new ImportEventJob($event));
            }
        }
    }

    /**
     * Import results for the given event
     * @param DirtEvent $event
     */
    public function getEvent(DirtEvent $event)
    {
        if ($event->racenet_event_id) {
            \Log::info('Loading results for event from website : Begin', ['id' => $event->id]);
            // Remember when we started processing this event
            $this->startEventImport($event);

            $dirtEvent = $this->getPage($this->getShortEventPath($event));

            \Log::info('Found stages for event', ['event' => $event->id, 'stages' => $dirtEvent->TotalStages]);
            for ($stageNum = 1; $stageNum <= $dirtEvent->TotalStages; $stageNum++) {
                $this->processStage($event, $stageNum);
            }

            $this->completeEventImport($event);
            \Log::info('Loading results for event from website : End', ['id' => $event->id]);
        }
    }

    /**
     * Process the given stage for the given event
     * @param DirtEvent $event
     * @param integer $stageNum
     */
    protected function processStage(DirtEvent $event, $stageNum)
    {
        $stage = $this->getStage($event, $stageNum);

        if ($stage) {
            // Get the first page
            $page = $this->getPage($this->getEventPath($event, $stageNum));

            \Log::info('Found pages for stage', ['stage' => $stage->id, 'pages' => $page->Pages]);
            if ($page->Pages > 0) {
                $this->clearStageResults($stage);
                $this->processPage($stage, $page);
                for ($pageNum = 2; $pageNum <= $page->Pages; $pageNum++) {
                    $page = $this->getPage($this->getEventPath($event, $stageNum, $pageNum));
                    $this->processPage($stage, $page);
                }
                \DirtRallyPositions::updateStagePositions($stage);
            }
        }
    }

    /**
     * Process a single page for the given stage
     * @param DirtStage $stage
     * @param \stdClass $page
     */
    protected function processPage(DirtStage $stage, $page)
    {
        \Log::info('Processing page', ['stage' => $stage->id, 'entries' => count($page->Entries)]);
        foreach($page->Entries as $entry) {
            $this->processResult(
                $stage,
                $entry->Name,
                $this->getRacenetID($entry->ProfileUrl),
                $entry->Time,
                $entry->NationalityImage,
                $entry->VehicleName
            );
        }
    }

    /**
     * Process a result ready for saving
     * @param DirtStage $stage
     * @param string $driverName
     * @param string $racenetID
     * @param string $timeString
     * @param string $nationalityImage
     * @param string $car
     */
    protected function processResult(DirtStage $stage, $driverName, $racenetID, $timeString, $nationalityImage, $car)
    {
        // Get the driver model
        $driver = $this->getDriverByRacenetID($racenetID, $driverName);
        $this->processDriver($driver, $nationalityImage);
        // Convert the time to an integer
        $timeInt = \Times::fromString($timeString);

        if ($stage->order != 1) {
            // 2nd stage onwards is cumulative time
            // Subtract the sum of previous times
            $sub = 0;
            for ($i = 1; $i <= ($stage->order - 1); $i++) {
                $previousStage = $this->stages[$stage->event->id][$i];
                $sub += $this->results[$previousStage->id][$driver->id]->time;
            }

            $timeInt -= $sub;
        }

        $this->saveResult($stage, $driver, $timeInt, $car);
    }

    /**
     * Process a driver, update their nation
     * @param \App\Models\Driver $driver
     * @param string $nationalityImage Relative path to the flag
     */
    protected function processDriver(\App\Models\Driver $driver, $nationalityImage)
    {
        // Only update the driver's nation if they're not locked
        if (!$driver->locked) {
            $dirtReference = basename($nationalityImage, '.jpg');
            /** @var \App\Models\Nation $nation */
            $nation = \Nations::findOrAdd($dirtReference, 'https://www.dirtgame.com' . $nationalityImage);
            $driver->nation()->associate($nation);
            $driver->save();
        }
    }

    /**
     * Get the URL for getting info about an event
     * @param DirtEvent $event
     * @param int $stage
     * @return string
     */
    protected function getShortEventPath(DirtEvent $event, $stage = 0)
    {
        return $this->getShortEventPathFromID($event->racenet_event_id, $stage);
    }

    /**
     * Get the URL for getting info about an event
     * @param int $eventID
     * @param int $stage
     * @return string
     */
    protected function getShortEventPathFromID($eventID, $stage)
    {
        return 'https://www.dirtgame.com/uk/api/event?eventId='.$eventID.'&stageId='.$stage;
    }

    /**
     * Get the URL for importing results
     * @param DirtEvent $event
     * @param int $stage
     * @param int $page
     * @return string
     */
    protected function getEventPath(DirtEvent $event, $stage = 1, $page = 1)
    {
        return $this->getShortEventPath($event, $stage)
            .'&assists=any&group=all&leaderboard=true&nameSearch=&wheel=any&page='.$page;
    }

    /**
     * Get a page from the given url, decode it, log how long it took
     * @param $url
     * @return \stdClass
     */
    protected function getPage($url)
    {
        $start = microtime(true);
        $file = json_decode(file_get_contents($url));
        \Log::info('Page loaded', ['time' => (microtime(true) - $start)]);
        return $file;
    }

    /**
     * Get the racenet ID from the profile URL
     * @param  string $url
     * @return string
     */
    protected function getRacenetID($url)
    {
        return basename($url);
    }

    /**
     * Import stage names from events that use all the stages...
     */
    public function getStageNames()
    {
        /*
         * These events have been set up to cover all current DiRT stages and tracks.
         * If for some reason they don't work, set up some new ones and replace the
         * IDs. There's no results; just a list of stages to be parsed and read.
         */
        $eventIDs = [
            165504, # greece
            165505, # germany
            165506, # finland
            165507, # monaco
            165508, # wales
            165509, # sweden

            165513, # vvv rallycross vvv
            165514,
            165515,
            165516,
            165517,
            165518,
            165519,
            165520, # ^^^ rallycross ^^^

            165522, # Hillclimb (Pikes Peak)
        ];

        foreach($eventIDs AS $eventID) {
            $page = $this->getPage($this->getShortEventPathFromID($eventID, 0));
            $totalStages = $page->TotalStages;
            for ($stageID = 1; $stageID <= $totalStages; $stageID++) {
                $page = $this->getPage($this->getShortEventPathFromID($eventID, $stageID));

                DirtStageInfo::create([
                    'location_name' => $page->LocationName,
                    'stage_name' => $page->StageName,

                ]);
            }
        }
    }

    public function importEventDetails(DirtEvent $event)
    {
        if ($event->stages->count()) {
            \Log::info('Not importing event details for '.$event->id.' : stages already exist');
            return false;
        }

        $page = $this->getPage($this->getShortEventPath($event));
        for ($stage = 1; $stage <= $page->TotalStages; $stage++) {
            $this->importStageDetails($event, $stage);
        }
    }

    protected function importStageDetails(DirtEvent $event, $stageNumber)
    {
        $page = $this->getPage($this->getShortEventPath($event, $stageNumber));

        // match the stageInfo
        $stageInfo = DirtStageInfo::where('location_name', $page->LocationName)
            ->where('stage_name', $page->StageName)
            ->first();

        if ($stageInfo) {
            $stage = new DirtStage([
                'order' => $stageNumber,
                'time_of_day' => $page->TimeOfDay,
                'weather' => $page->WeatherText,
            ]);

            $stage->stageInfo()->associate($stageInfo);
            $event->stages()->save($stage);
        } else {
            \Log::info('Not importing stage details for '.$event->id.':'.$stageNumber.' : could not match the stage information');
        }
    }

    /**
     * Check all stages through the Dirt Rally website and update them
     */
    public function updateAllStages()
    {
        foreach(DirtStage::all() AS $stage) {
            $this->updateStageDetails($stage);
        }
    }

    /**
     * Update the given stage with details from the Dirt Rally website
     * @param DirtStage $stage
     */
    protected function updateStageDetails(DirtStage $stage)
    {
        $page = $this->getPage($this->getShortEventPath($stage->event, $stage->order));
        $stage->fill([
            'time_of_day' => $page->TimeOfDay,
            'weather' => $page->WeatherText,
        ])->save();
    }

}
