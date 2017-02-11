<?php

namespace App\Listeners\AcHotlap;

use App\Events\Races\CarUpdated;
use App\Events\AcHotlap\SessionUpdated;
use App\Events\DriverUpdated;
use App\Events\NationUpdated;
use App\Models\AcHotlap\AcHotlapSessionEntrant;
use App\Models\Driver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class Results implements ShouldQueue
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            DriverUpdated::class,
            'App\Listeners\AcHotlap\Results@clearDrivers'
        );
        $events->listen(
            SessionUpdated::class,
            'App\Listeners\AcHotlap\Results@clearSessionCache'
        );
        $events->listen(
            NationUpdated::class,
            'App\Listeners\AcHotlap\Results@clearNationSessions'
        );
        $events->listen(
            CarUpdated::class,
            'App\Listeners\AcHotlap\Results@clearCarEntrants'
        );
    }

    /**
     * Clear results when a driver is updated
     * @param DriverUpdated $event
     */
    public function clearDrivers(DriverUpdated $event)
    {
        $this->clearDriverSessions($event->driver);
    }

    /**
     * Clear results for a session
     * @param SessionUpdated $event
     */
    public function clearSessionCache(SessionUpdated $event)
    {
        \AcHotlapCacheHandler::clearSessionCache($event->session);
    }

    /**
     * Clear all results that contain a given nation
     * @param NationUpdated $event
     */
    public function clearNationSessions(NationUpdated $event)
    {
        foreach($event->nation->drivers AS $driver) {
            $this->clearDriverSessions($driver);
        }
    }

    /**
     * Clear each session that a driver was in
     * @param Driver $driver
     */
    private function clearDriverSessions(Driver $driver)
    {
        $this->getEntrantsAndClear(AcHotlapSessionEntrant::where('driver_id', $driver->id));
    }

    /**
     * Clear all results that have a certain car
     * @param CarUpdated $event
     */
    private function clearCarEntrants(CarUpdated $event)
    {
        $this->getEntrantsAndClear(AcHotlapSessionEntrant::where('car_id', $event->car->id));
    }

    /**
     * Take a query filtering the AcHotlapSessionEntrant table, get all the entrants, and clear all the sessions
     * @param $query
     */
    private function getEntrantsAndClear($query)
    {
        $sessions = $query->get()->map(function ($entrant) {
            return $entrant->session;
        });

        foreach($sessions->unique() AS $session) {
            \AcHotlapCacheHandler::clearSessionCache($session);
        }
    }
}