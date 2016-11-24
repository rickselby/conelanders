<?php

namespace App\Listeners\RallyCross;

use App\Events\Event;
use App\Events\RallyCross\CarUpdated;
use App\Events\RallyCross\ChampionshipUpdated;
use App\Events\RallyCross\EventEntrantsUpdated;
use App\Events\RallyCross\EventUpdated;
use App\Events\RallyCross\SessionUpdated;
use App\Events\DriverUpdated;
use App\Events\NationUpdated;
use App\Models\Driver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class Results implements ShouldQueue
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            DriverUpdated::class,
            'App\Listeners\RallyCross\Results@clearDrivers'
        );
        $events->listen(
            SessionUpdated::class,
            'App\Listeners\RallyCross\Results@clearSessionCache'
        );
        $events->listen(
            EventUpdated::class,
            'App\Listeners\RallyCross\Results@clearEventCache'
        );
        $events->listen(
            EventEntrantsUpdated::class,
            'App\Listeners\RallyCross\Results@clearEventCache'
        );
        $events->listen(
            ChampionshipUpdated::class,
            'App\Listeners\RallyCross\Results@clearChampionshipSessionsCache'
        );
        $events->listen(
            NationUpdated::class,
            'App\Listeners\RallyCross\Results@clearNationSessions'
        );
        $events->listen(
            CarUpdated::class,
            'App\Listeners\RallyCross\Results@clearCarEntrants'
        );
    }

    /**
     * Clear results when a driver is updated
     * @param DriverUpdated $event
     */
    public function clearDrivers(DriverUpdated $event)
    {
        $event->driver->load('rallyCrossResults.session');
        $this->clearDriverSessions($event->driver);
    }

    /**
     * Clear results for a session
     * @param SessionUpdated $event
     */
    public function clearSessionCache(SessionUpdated $event)
    {
        \RXCacheHandler::clearSessionCache($event->session);
    }

    /**
     * Clear results for an event
     * @param EventUpdated $event
     */
    public function clearEventCache(EventUpdated $event)
    {
        \RXCacheHandler::clearEventCache($event->event);
    }

    /**
     * Clear all results for a championship (down to each session)
     * @param Event $event
     */
    public function clearChampionshipSessionsCache(Event $event)
    {
        foreach($event->championship->events AS $champEvent) {
            foreach($champEvent->sessions AS $session) {
                \RXCacheHandler::clearSessionCache($session);
            }
        }
    }

    /**
     * Clear all results that contain a given nation
     * @param NationUpdated $event
     */
    public function clearNationSessions(NationUpdated $event)
    {
        $event->nation->load('drivers.rallyCrossResults.session');
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
        foreach($driver->rallyCrossResults AS $sessionEntry) {
            \RXCacheHandler::clearSessionCache($sessionEntry->session);
        }
    }

    /**
     * Clear all results that have a certain car
     * @param CarUpdated $event
     */
    public function clearCarEntrants(CarUpdated $event)
    {
        /** @var Collection $sessions */
        $sessions = collect($event->car->entrants)->map(function ($entrant) {
            return $entrant->session;
        });

        foreach($sessions->unique() AS $session) {
            \RXCacheHandler::clearSessionCache($session);
        }
    }
}