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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImportResults
{
    protected $stages;

    public function getAllEvents()
    {
        foreach(Event::all() as $event) {
            // filter them here
            $this->getEvent($event);
        }
    }

    public function getEvent(Event $event)
    {
        if ($event->dirt_id) {
            $dirtEvent = json_decode(file_get_contents($this->getShortEventPath($event)));
            for ($stageNum = 1; $stageNum <= $dirtEvent->TotalStages; $stageNum++) {
                $this->processStage($event, $stageNum);
            }
        }
    }

    protected function processStage(Event $event, $stageNum)
    {
        // Get the first page
        $page = json_decode(file_get_contents($this->getEventPath($event, $stageNum)));
        $this->processPage($event, $page, $stageNum);
        for ($pageNum = 2; $pageNum <= $page->Pages; $pageNum++) {
            $page = json_decode(file_get_contents($this->getEventPath($event, $stageNum, $pageNum)));
            $this->processPage($event, $page, $stageNum);
        }
    }

    protected function processPage(Event $event, $page, $stage)
    {
        foreach($page->Entries as $entry) {
            $this->saveResult($event, $stage, $entry->Name, $entry->Time);
        }
    }

    protected function saveResult(Event $event, $stageNumber, $driverName, $timeString)
    {
        $driver = $this->getDriver($driverName);
        $timeInt = \StageTime::fromString($timeString);

        $stage = $this->getStage($event, $stageNumber);

        try {
            $results = $stage->results()->where('driver_id', $driver->id)->firstOrFail();
            $results->time = $timeInt;
            $results->save();
        } catch (ModelNotFoundException $e) {
            $results = Result::create(['driver_id' => $driver->id, 'time' => $timeInt]);
            $stage->results()->save($results);
        }
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

    protected function getDriver($driverName)
    {
        try {
            $driver = Driver::where('name', $driverName)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $driver = Driver::create(['name' => $driverName]);
        }

        return $driver;
    }

    protected function getStage(Event $event, $stageNumber)
    {
        if (!isset($this->stages[$event->id][$stageNumber])) {
            $this->stages[$event->id][$stageNumber] = $event->stages()->where('order', $stageNumber)->first();
        }

        return $this->stages[$event->id][$stageNumber];
    }

}
