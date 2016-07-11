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
     * Each event should have a final import 2-3 minutes before it closes
     * (just in case we can't get the results after this time)
     * This gets hit every minute (ouch) to see if this is true for any current event.
     */
    public function queueLastImport()
    {
        foreach(DirtEvent::with('stages.results')->get() as $event) {
            $now = Carbon::now();
            if ($now->between(
                $event->closes->copy()->subMinutes(5),
                $event->closes->copy()->subMinutes(4)->addSecond(),
                true)) {
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
            $this->processResult($stage, $entry->Name, $this->getRacenetID($entry->ProfileUrl), $entry->Time, $entry->NationalityImage);
        }
    }

    /**
     * Process a result ready for saving
     * @param DirtStage $stage
     * @param string $driverName
     * @param string $racenetID
     * @param string $timeString
     * @param string $nationalityImage
     */
    protected function processResult(DirtStage $stage, $driverName, $racenetID, $timeString, $nationalityImage)
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

        $this->saveResult($stage, $driver, $timeInt);
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
        return 'https://www.dirtgame.com/uk/api/event?eventId='.$event->racenet_event_id.'&stageId='.$stage;
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

}
