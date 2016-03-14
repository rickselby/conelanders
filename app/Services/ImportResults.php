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

class ImportResults
{
    const SHORT_DNF = 900000; # (15*60*1000)
    const LONG_DNF = 1800000; # (30*60*1000)

    /** @var Stage[] */
    protected $stages;

    /** @var Driver[] */
    protected $drivers;

    /** @var Result[] */
    protected $results;

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

            // Cache the stages for lookup on order
            foreach($event->stages AS $stage) {
                $this->stages[$event->id][$stage->order] = $stage;
            }

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
        foreach($stage->results AS $result) {
            $this->results[$stage->id][$result->driver_id] = $result;
        }

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
            $this->saveResult($stage, $entry->Name, $entry->Time);
        }
    }

    protected function saveResult(Stage $stage, $driverName, $timeString)
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

        if (!isset($this->results[$stage->id][$driver->id])) {
            $this->results[$stage->id][$driver->id] = Result::create(
                ['driver_id' => $driver->id]
            );
            $stage->results()->save($this->results[$stage->id][$driver->id]);
        }

        if (($stage->long && $timeInt == self::LONG_DNF) || (!$stage->long && $timeInt == self::SHORT_DNF)) {
            $this->results[$stage->id][$driver->id]->dnf = true;
        } else {
            $this->results[$stage->id][$driver->id]->time = $timeInt;
        }
        $this->results[$stage->id][$driver->id]->save();
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
        if (count($this->drivers) == 0) {
            foreach(Driver::all() AS $driver) {
                $this->drivers[$driver->name] = $driver;
            }
        }

        if (!isset($this->drivers[$driverName])) {
            $this->drivers[$driverName] = Driver::create(['name' => $driverName]);
        }

        return $this->drivers[$driverName];
    }

    protected function getStage(Event $event, $stageNumber)
    {
        if (!isset($this->stages[$event->id][$stageNumber])) {
            $this->stages[$event->id][$stageNumber] = $event->stages()->where('order', $stageNumber)->first();
        }

        return $this->stages[$event->id][$stageNumber];
    }

}
