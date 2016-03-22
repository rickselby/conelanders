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

namespace App\Services;

use App\Jobs\ImportEventJob;
use App\Models\Event;
use App\Models\Stage;
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
        foreach(Event::with('stages.results')->get() as $event) {
            // only start checking events after they open
            // only check events if they're not marked as complete
            if ($event->dirt_id
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
        foreach(Event::with('stages.results')->get() as $event) {
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
     * @param Event $event
     */
    public function getEvent(Event $event)
    {
        if ($event->dirt_id) {
            \Log::info('Loading results for event '.$event->id.' from website : Begin');
            // Remember when we started processing this event
            $this->startEventImport($event);

            $dirtEvent = $this->getPage($this->getShortEventPath($event));

            \Log::info($dirtEvent->TotalStages.' stages to process');
            for ($stageNum = 1; $stageNum <= $dirtEvent->TotalStages; $stageNum++) {
                $this->processStage($event, $stageNum);
            }

            $this->completeEventImport($event);
            \Log::info('Loading results for event '.$event->id.' from website : End');
        }
    }

    /**
     * Process the given stage for the given event
     * @param Event $event
     * @param integer $stageNum
     */
    protected function processStage(Event $event, $stageNum)
    {
        $stage = $this->getStage($event, $stageNum);

        if ($stage) {
            // Get the first page
            $page = $this->getPage($this->getEventPath($event, $stageNum));

            \Log::info('Stage '.$stage->id.' has '.$page->Pages.' pages to process');
            if ($page->Pages > 0) {
                $this->clearStageResults($stage);
                $this->processPage($stage, $page);
                for ($pageNum = 2; $pageNum <= $page->Pages; $pageNum++) {
                    $page = $this->getPage($this->getEventPath($event, $stageNum, $pageNum));
                    $this->processPage($stage, $page);
                }
                \Positions::updateStagePositions($stage);
            }
        }
    }

    /**
     * Process a single page for the given stage
     * @param Stage $stage
     * @param \stdClass $page
     */
    protected function processPage(Stage $stage, $page)
    {
        \Log::info('Processing page for stage '.$stage->id.': '.count($page->Entries).' entries');
        foreach($page->Entries as $entry) {
            $this->processResult($stage, $entry->Name, $entry->Time);
        }
    }

    /**
     * Process a result ready for saving
     * @param Stage $stage
     * @param string $driverName
     * @param string $timeString
     */
    protected function processResult(Stage $stage, $driverName, $timeString)
    {
        // Get the driver model
        $driver = $this->getDriver($driverName);
        // Convert the time to an integer
        $timeInt = \StageTime::fromString($timeString);

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
     * Get the URL for getting info about an event
     * @param Event $event
     * @param int $stage
     * @return string
     */
    protected function getShortEventPath(Event $event, $stage = 0)
    {
        return 'https://www.dirtgame.com/uk/api/event?eventId='.$event->dirt_id.'&stageId='.$stage;
    }

    /**
     * Get the URL for importing results
     * @param Event $event
     * @param int $stage
     * @param int $page
     * @return string
     */
    protected function getEventPath(Event $event, $stage = 1, $page = 1)
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
        \Log::info('Page took '.(microtime(true) - $start).'s to load');
        return $file;
    }

}