<?php

namespace App\Services\Cached\Races;

use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesSession;
use App\Models\Driver;
use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Cache\Repository;

class Handler
{
    /**
     * @var Repository
     */
    protected $cache;

    protected $cacheKey = 'races.tag.';

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the tag for a session
     * @param RacesSession $session
     * @return string
     */
    public function sessionTag(RacesSession $session)
    {
        return $this->cacheKey.'session.'.$session->id;
    }

    /**
     * Get the tag for an event
     * @param RacesEvent $event
     * @return string
     */
    public function eventTag(RacesEvent $event)
    {
        return $this->cacheKey.'event.'.$event->id;
    }

    /**
     * Get the tag for an championship
     * @param RacesChampionship $championship
     * @return string
     */
    public function championshipTag(RacesChampionship $championship)
    {
        return $this->cacheKey.'championship.'.$championship->id;
    }

    /**
     * Get the tag for a drivers' results
     * @param Driver $driver
     * @return string
     */
    public function driverKey(Driver $driver)
    {
        return $this->cacheKey.'driver.'.$driver->id;
    }

    /**
     * Clear the cache for a session, and clear parents
     * @param RacesSession $session
     */
    public function clearSessionCache(RacesSession $session)
    {
        if ($this->checkCacheStoreSupportsTags()) {
            \Log::info('Clearing Races Session Cache', ['session' => $session->id]);
            $this->cache->tags($this->sessionTag($session))->flush();
        }
        $this->clearEventCache($session->event);
    }

    /**
     * Clear the cache for an event, and clear parents
     * @param RacesEvent $event
     */
    public function clearEventCache(RacesEvent $event)
    {
        if ($this->checkCacheStoreSupportsTags()) {
            \Log::info('Clearing Races Event Cache', ['event' => $event->id]);
            $this->cache->tags($this->eventTag($event))->flush();
        }
        $this->clearChampionshipCache($event->championship);
    }

    /**
     * Clear the cache for a championship, and each entrants' driver cache
     * @param RacesChampionship $championship
     */
    public function clearChampionshipCache(RacesChampionship $championship)
    {
        if ($this->checkCacheStoreSupportsTags()) {
            \Log::info('Clearing Races Championship Cache', ['championship' => $championship->id]);
            $this->cache->tags($this->championshipTag($championship))->flush();
        }
        foreach($championship->entrants AS $entrant) {
            $this->cache->forget($this->driverKey($entrant->driver));
        }
    }

    private function checkCacheStoreSupportsTags()
    {
        return $this->cache->getStore() instanceof TaggableStore;
    }

}