<?php

namespace App\Listeners\Races;

use App\Events\Races\CarUpdated;
use App\Events\Races\ChampionshipEntrantsUpdated;
use App\Events\Races\ChampionshipTeamsUpdated;
use App\Events\Races\ChampionshipUpdated;
use App\Events\Races\EventUpdated;
use App\Events\Races\SessionUpdated;
use App\Events\DriverUpdated;
use App\Events\Event;
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
            'App\Listeners\Races\Results@clearDrivers'
        );
        $events->listen(
            SessionUpdated::class,
            'App\Listeners\Races\Results@clearSessionCache'
        );
        $events->listen(
            EventUpdated::class,
            'App\Listeners\Races\Results@clearEventCache'
        );
        $events->listen(
            ChampionshipUpdated::class,
            'App\Listeners\Races\Results@clearChampionshipSessionsCache'
        );
        $events->listen(
            ChampionshipEntrantsUpdated::class,
            'App\Listeners\Races\Results@clearChampionshipSessionsCache'
        );
        $events->listen(
            ChampionshipTeamsUpdated::class,
            'App\Listeners\Races\Results@clearChampionshipSessionsCache'
        );
        $events->listen(
            NationUpdated::class,
            'App\Listeners\Races\Results@clearNationSessions'
        );
        $events->listen(
            CarUpdated::class,
            'App\Listeners\Races\Results@clearCarEntrants'
        );
    }

    /**
     * Clear results when a driver is updated
     * @param DriverUpdated $event
     */
    public function clearDrivers(DriverUpdated $event)
    {
        $event->driver->load('raceEntries.entries.session');
        $this->clearDriverSessions($event->driver);
    }

    /**
     * Clear results for a session
     * @param SessionUpdated $event
     */
    public function clearSessionCache(SessionUpdated $event)
    {
        \RacesCacheHandler::clearSessionCache($event->session);
    }

    /**
     * Clear results for an event
     * @param EventUpdated $event
     */
    public function clearEventCache(EventUpdated $event)
    {
        \RacesCacheHandler::clearEventCache($event->event);
    }

    /**
     * Clear all results for a championship (down to each session)
     * @param ChampionshipEntrantsUpdated $event
     */
    public function clearChampionshipSessionsCache(Event $event)
    {
        foreach($event->championship->events AS $champEvent) {
            foreach($champEvent->sessions AS $session) {
                \RacesCacheHandler::clearSessionCache($session);
            }
        }
    }

    /**
     * Clear all results that contain a given nation
     * @param NationUpdated $event
     */
    public function clearNationSessions(NationUpdated $event)
    {
        $event->nation->load('drivers.raceEntries.entries.session');
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
        foreach($driver->raceEntries AS $champEntry) {
            foreach($champEntry->entries AS $sessionEntry) {
                \RacesCacheHandler::clearSessionCache($sessionEntry->session);
            }
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
            \RacesCacheHandler::clearSessionCache($session);
        }
    }
}