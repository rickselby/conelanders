<?php

namespace App\Listeners\AssettoCorsa;

use App\Events\AssettoCorsa\ChampionshipEntrantsUpdated;
use App\Events\AssettoCorsa\ChampionshipUpdated;
use App\Events\AssettoCorsa\EventUpdated;
use App\Events\AssettoCorsa\SessionUpdated;
use App\Events\DriverUpdated;
use App\Events\NationUpdated;
use App\Models\Driver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class Results implements ShouldQueue
{
    protected $acResultsCache;

    public function __construct(\App\Services\Cached\AssettoCorsa\Results $acResultsCache)
    {
        $this->acResultsCache = $acResultsCache;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            DriverUpdated::class,
            'App\Listeners\AssettoCorsa\Results@clearDrivers'
        );
        $events->listen(
            SessionUpdated::class,
            'App\Listeners\AssettoCorsa\Results@clearSessionCache'
        );
        $events->listen(
            EventUpdated::class,
            'App\Listeners\AssettoCorsa\Results@clearEventCache'
        );
        $events->listen(
            ChampionshipUpdated::class,
            'App\Listeners\AssettoCorsa\Results@clearChampionshipCache'
        );
        $events->listen(
            ChampionshipEntrantsUpdated::class,
            'App\Listeners\AssettoCorsa\Results@clearChampionshipSessionsCache'
        );
        $events->listen(
            NationUpdated::class,
            'App\Listeners\AssettoCorsa\Results@clearNationSessions'
        );
    }

    /**
     * Clear AC results when a driver is updated
     * @param DriverUpdated $event
     */
    public function clearDrivers(DriverUpdated $event)
    {
        $event->driver->load('acEntries.entries.session');
        $this->clearDriverSessions($event->driver);
    }

    /**
     * Clear AC results for a session
     * @param SessionUpdated $event
     */
    public function clearSessionCache(SessionUpdated $event)
    {
        \ACCacheHandler::clearSessionCache($event->session);
    }

    /**
     * Clear AC results for an event
     * @param EventUpdated $event
     */
    public function clearEventCache(EventUpdated $event)
    {
        \ACCacheHandler::clearEventCache($event->event);
    }

    /**
     * Clear AC results for a championship
     * @param ChampionshipUpdated $event
     */
    public function clearChampionshipCache(ChampionshipUpdated $event)
    {
        \ACCacheHandler::clearChampionshipCache($event->championship);
    }

    /**
     * Clear all AC results for a championship (down to each session)
     * @param ChampionshipEntrantsUpdated $event
     */
    public function clearChampionshipSessionsCache(ChampionshipEntrantsUpdated $event)
    {
        foreach($event->championship->events AS $champEvent) {
            foreach($champEvent->session AS $session) {
                \ACCacheHandler::clearSessionCache($session);
            }
        }
    }

    /**
     * Clear all AC results that contain a given nation
     * @param NationUpdated $event
     */
    public function clearNationSessions(NationUpdated $event)
    {
        $event->nation->load('drivers.acEntries.entries.session');
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
        foreach($driver->acEntries AS $champEntry) {
            foreach($champEntry->entries AS $sessionEntry) {
                \ACCacheHandler::clearSessionCache($sessionEntry->session);
            }
        }
    }
}