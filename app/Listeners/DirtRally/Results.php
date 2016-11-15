<?php

namespace App\Listeners\DirtRally;

use App\Events\DirtRally\ChampionshipUpdated;
use App\Events\DirtRally\EventUpdated;
use App\Events\DirtRally\SeasonUpdated;
use App\Events\DirtRally\StageUpdated;
use App\Events\DriverUpdated;
use App\Events\NationUpdated;
use App\Models\Driver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class Results implements ShouldQueue
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            DriverUpdated::class,
            'App\Listeners\DirtRally\Results@clearDrivers'
        );
        $events->listen(
            StageUpdated::class,
            'App\Listeners\DirtRally\Results@clearStageCache'
        );
        $events->listen(
            EventUpdated::class,
            'App\Listeners\DirtRally\Results@clearEventCache'
        );
        $events->listen(
            SeasonUpdated::class,
            'App\Listeners\DirtRally\Results@clearSeasonCache'
        );
        $events->listen(
            ChampionshipUpdated::class,
            'App\Listeners\DirtRally\Results@clearChampionshipCache'
        );
        $events->listen(
            NationUpdated::class,
            'App\Listeners\DirtRally\Results@clearNationStages'
        );
    }

    /**
     * Clear results when a driver is updated
     * @param DriverUpdated $event
     */
    public function clearDrivers(DriverUpdated $event)
    {
        $event->driver->load('raceEntries.entries.session');
        $this->clearDriverStages($event->driver);
    }

    /**
     * Clear results for a session
     * @param StageUpdated $event
     */
    public function clearStageCache(StageUpdated $event)
    {
        \DirtRallyCacheHandler::clearStageCache($event->stage);
    }

    /**
     * Clear results for an event
     * @param EventUpdated $event
     */
    public function clearEventCache(EventUpdated $event)
    {
        \DirtRallyCacheHandler::clearEventCache($event->event);
    }

    /**
     * Clear results for a season
     * @param SeasonUpdated $event
     */
    public function clearSeasonCache(SeasonUpdated $event)
    {
        \DirtRallyCacheHandler::clearSeasonCache($event->season);
    }

    /**
     * Clear results for a championship
     * @param ChampionshipUpdated $event
     */
    public function clearChampionshipCache(ChampionshipUpdated $event)
    {
        \DirtRallyCacheHandler::clearChampionshipCache($event->championship);
    }

    /**
     * Clear all results that contain a given nation
     * @param NationUpdated $event
     */
    public function clearNationStages(NationUpdated $event)
    {
        $event->nation->load('drivers.raceEntries.entries.session');
        foreach($event->nation->drivers AS $driver) {
            $this->clearDriverStages($driver);
        }
    }

    /**
     * Clear each stage that a driver was in
     * @param Driver $driver
     */
    private function clearDriverStages(Driver $driver)
    {
        foreach($driver->dirtResults AS $result) {
            \DirtRallyCacheHandler::clearStageCache($result->stage);
        }
    }
}