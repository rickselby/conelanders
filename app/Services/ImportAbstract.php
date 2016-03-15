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

abstract class ImportAbstract
{
    const SHORT_DNF = 900000; # (15*60*1000)
    const LONG_DNF = 1800000; # (30*60*1000)

    /** @var Stage[] */
    protected $stages;

    /** @var Driver[] */
    protected $drivers;

    /** @var Result[] */
    protected $results;

    /**
     * Get a driver from their name
     * @param string $driverName
     * @return Driver
     */
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

    protected function saveResult(Stage $stage, Driver $driver, $timeInt)
    {
        if (!isset($this->results[$stage->id][$driver->id])) {
            $this->results[$stage->id][$driver->id] = Result::create(
                ['driver_id' => $driver->id]
            );
            $stage->results()->save($this->results[$stage->id][$driver->id]);
        }

        if (($stage->long && $timeInt == self::LONG_DNF) || (!$stage->long && $timeInt == self::SHORT_DNF)) {
            $this->results[$stage->id][$driver->id]->dnf = true;
            $this->results[$stage->id][$driver->id]->time = 0;
        } else {
            $this->results[$stage->id][$driver->id]->time = $timeInt;
            $this->results[$stage->id][$driver->id]->dnf = false;
        }
        $this->results[$stage->id][$driver->id]->save();
    }

    protected function cacheStages(Event $event)
    {
        foreach($event->stages AS $stage) {
            $this->stages[$event->id][$stage->order] = $stage;
            $this->cacheResults($stage);
        }
    }

    protected function cacheResults(Stage $stage)
    {
        foreach($stage->results AS $result) {
            $this->results[$stage->id][$result->driver_id] = $result;
        }
    }

}