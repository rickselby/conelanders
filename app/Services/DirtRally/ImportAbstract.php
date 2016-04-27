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

use App\Models\Driver;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtResult;
use App\Models\DirtRally\DirtStage;
use Carbon\Carbon;

abstract class ImportAbstract
{
    const SHORT_DNF = 900000; # (15*60*1000)
    const LONG_DNF = 1800000; # (30*60*1000)

    /** @var DirtStage[] Stages keyed by stage order */
    protected $stages;

    /** @var Driver[] Drivers keyed by driver name */
    protected $drivers;

    /** @var DirtResult[] Results keyed by stage id and driver id */
    protected $results;

    /** @var Carbon */
    private $importStartTime;

    /** @var boolean Flag if data was imported in this run */
    private $imported;

    /**
     * Get a driver from their name
     * @param  string $driverName
     * @param  string $racenetID [optional]
     * @return Driver
     */
    protected function getDriver($driverName, $racenetID = NULL)
    {
        $this->initialiseDrivers();

        if (!isset($this->drivers['names'][$driverName])) {
            $this->drivers['names'][$driverName] = Driver::create(['name' => $driverName, 'dirt_racenet_driver_id' => $racenetID]);
        } elseif ($racenetID !== NULL) {
            // Update the driver with racenet ID if required
            $this->drivers['names'][$driverName]->dirt_racenet_driver_id = $racenetID;
            $this->drivers['names'][$driverName]->save();
        }

        return $this->drivers['names'][$driverName];
    }

    /**
     * Get a driver by their racenet ID
     * @param  string $racenetID
     * @param  string $driverName
     * @return Driver
     */
    protected function getDriverByRacenetID($racenetID, $driverName)
    {
        $this->initialiseDrivers();

        if (isset($this->drivers['ids'][$racenetID])) {
            // Update the driver name, if required
            if ($this->drivers['ids'][$racenetID]->name != $driverName) {
                $this->drivers['ids'][$racenetID]->name = $driverName;
                $this->drivers['ids'][$racenetID]->save();
            }
            return $this->drivers['ids'][$racenetID];
        } else {
            return $this->getDriver($driverName, $racenetID);
        }
    }

    /**
     * Pull in drivers, key by names / racenet IDs
     */
    protected function initialiseDrivers()
    {
        if (count($this->drivers) == 0) {
            $this->drivers = [
                'names' => [],
                'ids' => [],
            ];

            foreach(Driver::all() AS $driver) {
                $this->drivers['names'][$driver->name] = $driver;
                if ($driver->dirt_racenet_driver_id) {
                    $this->drivers['ids'][$driver->dirt_racenet_driver_id] = $driver;
                }
            }
        }
    }

    /**
     * Get a stage based on it's order number
     * @param DirtEvent $event
     * @param integer $stageNumber
     * @return DirtStage
     */
    protected function getStage(DirtEvent $event, $stageNumber)
    {
        if (!isset($this->stages[$event->id][$stageNumber])) {
            $this->stages[$event->id][$stageNumber] = $event->stages()->where('order', $stageNumber)->first();
        }

        return $this->stages[$event->id][$stageNumber];
    }

    /**
     * Save the time for the given driver for the given stage
     * @param DirtStage $stage
     * @param Driver $driver
     * @param integer $timeInt
     */
    protected function saveResult(DirtStage $stage, Driver $driver, $timeInt)
    {
        // Update the flag to show the import has imported something
        $this->imported = true;
        // Create a result model; cache it for future stuff (if importing from website)
        $this->results[$stage->id][$driver->id] = DirtResult::create(
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
     * @param DirtEvent $event
     */
    private function cacheStages(DirtEvent $event)
    {
        foreach($event->stages AS $stage) {
            $this->stages[$event->id][$stage->order] = $stage;
        }
    }

    /**
     * (Soft) Delete the results for the given stage
     * @param DirtStage $stage
     */
    protected function clearStageResults(DirtStage $stage)
    {
        $stage->results()->delete();
    }

    /**
     * Start an event importing
     * @param DirtEvent $event
     */
    protected function startEventImport(DirtEvent $event)
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
     * @param DirtEvent $event
     */
    protected function completeEventImport(DirtEvent $event)
    {
        // Set that the importing is finished
        $event->importing = false;
        // Check if we actually imported anything
        if ($this->imported) {
            // If we did, update the last_import time
            $event->last_import = $this->importStartTime;
        }
        $event->save();
        \DirtRallyPositions::updateEventPositions($event);
    }

}