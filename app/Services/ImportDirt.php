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

use App\Models\Driver;
use App\Models\Event;
use App\Models\Result;
use App\Models\Stage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImportDirt extends ImportAbstract
{
    public function getAllEvents()
    {
        foreach(Event::with('stages.results')->get() as $event) {
            // filter them here
            $this->getEvent($event);
        }
    }

    public function getEvent(Event $event)
    {
        if ($event->dirt_id) {
            $this->cacheStages($event);

            $dirtEvent = json_decode(file_get_contents($this->getShortEventPath($event)));

            for ($stageNum = 1; $stageNum <= $dirtEvent->TotalStages; $stageNum++) {
                $this->processStage($event, $stageNum);
            }
        }
    }

    protected function processStage(Event $event, $stageNum)
    {
        // Cache some stuff
        $stage = $this->getStage($event, $stageNum);

        // Get the first page
        $page = json_decode(file_get_contents($this->getEventPath($event, $stageNum)));
        $this->processPage($stage, $page);
        for ($pageNum = 2; $pageNum <= $page->Pages; $pageNum++) {
            $page = json_decode(file_get_contents($this->getEventPath($event, $stageNum, $pageNum)));
            $this->processPage($stage, $page);
        }
    }

    protected function processPage(Stage $stage, $page)
    {
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

}
