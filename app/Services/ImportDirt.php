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

    public function queueEventJobs()
    {
        foreach(Event::with('stages.results')->get() as $event) {
            // only start checking events after they open
            // only check events if they're not marked as complete
            if (($event->opens < Carbon::now())
                && !$event->complete) {
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

    public function getEvent(Event $event)
    {
        if ($event->dirt_id) {
            \Log::info('Loading results for event '.$event->id.' from website : Begin');
            // Remember when we started processing this event
            $startTime = Carbon::now();

            $this->cacheStages($event);

            $dirtEvent = $this->getPage($this->getShortEventPath($event));

            \Log::info($dirtEvent->TotalStages.' stages to process');
            for ($stageNum = 1; $stageNum <= $dirtEvent->TotalStages; $stageNum++) {
                $this->processStage($event, $stageNum);
            }

            // If we've processed this event after the closing date, mark it as
            // completed, so we don't process it again.
            if ($startTime > $event->closes) {
                $event->complete = true;
                $event->save();
            }
            \Log::info('Loading results for event '.$event->id.' from website : End');
        }
    }

    protected function processStage(Event $event, $stageNum)
    {
        // Cache some stuff
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
            }
        }
    }

    protected function processPage(Stage $stage, $page)
    {
        \Log::info('Processing page for stage '.$stage->id.': '.count($page->Entries).' entries');
        foreach($page->Entries as $entry) {
            $this->processResult($stage, $entry->Name, $entry->Time);
        }
    }

    protected function processResult(Stage $stage, $driverName, $timeString)
    {
        $driver = $this->getDriver($driverName);
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

    protected function getShortEventPath(Event $event, $stage = 0)
    {
        return 'https://www.dirtgame.com/uk/api/event?eventId='.$event->dirt_id.'&stageId='.$stage;
    }

    protected function getEventPath(Event $event, $stage = 1, $page = 1)
    {
        return $this->getShortEventPath($event, $stage)
            .'&assists=any&group=all&leaderboard=true&nameSearch=&wheel=any&page='.$page;
    }

    protected function getPage($url)
    {
        $start = microtime(true);
        $file = json_decode(file_get_contents($url));
        \Log::info('Page took '.(microtime(true) - $start).'s to load');
        return $file;
    }

}
