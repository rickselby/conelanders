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
use Carbon\Carbon;

abstract class ImportAbstract
{
    const SHORT_DNF = 900000; # (15*60*1000)
    const LONG_DNF = 1800000; # (30*60*1000)

    /** @var Stage[] Stages keyed by stage order */
    protected $stages;

    /** @var Driver[] Drivers keyed by driver name */
    protected $drivers;

    /** @var Result[] Results keyed by stage id and driver id */
    protected $results;

    /** @var Carbon */
    private $importStartTime;

    /** @var boolean Flag if data was imported in this run */
    private $imported;

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

    /**
     * Get a stage based on it's order number
     * @param Event $event
     * @param integer $stageNumber
     * @return Stage
     */
    protected function getStage(Event $event, $stageNumber)
    {
        if (!isset($this->stages[$event->id][$stageNumber])) {
            $this->stages[$event->id][$stageNumber] = $event->stages()->where('order', $stageNumber)->first();
        }

        return $this->stages[$event->id][$stageNumber];
    }

    /**
     * Save the time for the given driver for the given stage
     * @param Stage $stage
     * @param Driver $driver
     * @param integer $timeInt
     */
    protected function saveResult(Stage $stage, Driver $driver, $timeInt)
    {
        // Update the flag to show the import has imported something
        $this->imported = true;
        // Create a result model; cache it for future stuff (if importing from website)
        $this->results[$stage->id][$driver->id] = Result::create(
            ['driver_id' => $driver->id]
        );
        $stage->results()->save($this->results[$stage->id][$driver->id]);

        // Check if it's a DNF
        if (($stage->long && $timeInt == self::LONG_DNF) || (!$stage->long && $timeInt == self::SHORT_DNF)) {
            $this->results[$stage->id][$driver->id]->dnf = true;
            $this->results[$stage->id][$driver->id]->time = 0;
        } else {
            $this->results[$stage->id][$driver->id]->time = $timeInt;
            $this->results[$stage->id][$driver->id]->dnf = false;
        }
        $this->results[$stage->id][$driver->id]->save();
    }

    /**
     * Cache the stages, keyed by their order
     * @param Event $event
     */
    private function cacheStages(Event $event)
    {
        foreach($event->stages AS $stage) {
            $this->stages[$event->id][$stage->order] = $stage;
        }
    }

    /**
     * (Soft) Delete the results for the given stage
     * @param Stage $stage
     */
    protected function clearStageResults(Stage $stage)
    {
        $stage->results()->delete();
    }

    /**
     * Start an event importing
     * @param Event $event
     */
    protected function startEventImport(Event $event)
    {
        // Clear the imported flag
        $this->imported = false;
        // Set the start time for the import (for updating the last_import time, if import happens)
        $this->importStartTime = Carbon::now();
        // Set that the event is importing at the moment
        $event->importing = true;
        $event->save();
        // Cache the stages by order number
        $this->cacheStages($event);
    }

    /**
     * Mark an event import as complete
     * @param Event $event
     */
    protected function completeEventImport(Event $event)
    {
        // Set that the importing is finished
        $event->importing = false;
        // Check if we actually imported anything
        if ($this->imported) {
            // If we did, update the last_import time
            $event->last_import = $this->importStartTime;
        }
        $event->save();
    }

}