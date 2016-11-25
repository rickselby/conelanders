<?php

namespace App\Services\Cached\RallyCross;

use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;
use App\Models\Driver;
use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Cache\Repository;

class Handler
{
    /**
     * @var Repository
     */
    protected $cache;

    protected $cacheKey = 'rallycross.tag.';

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the tag for a session
     * @param RxSession $session
     * @return string
     */
    public function sessionTag(RxSession $session)
    {
        return $this->cacheKey.'session.'.$session->id;
    }

    /**
     * Get the tag for an event
     * @param RxEvent $event
     * @return string
     */
    public function eventTag(RxEvent $event)
    {
        return $this->cacheKey.'event.'.$event->id;
    }

    /**
     * Get the tag for an championship
     * @param RxChampionship $championship
     * @return string
     */
    public function championshipTag(RxChampionship $championship)
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
     * @param RxSession $session
     */
    public function clearSessionCache(RxSession $session)
    {
        if ($this->checkCacheStoreSupportsTags()) {
            \Log::info('Clearing RallyCross Session Cache', ['session' => $session->id]);
            $this->cache->tags($this->sessionTag($session))->flush();
        }
        $this->clearEventCache($session->event);
    }

    /**
     * Clear the cache for an event, and clear parents
     * @param RxEvent $event
     */
    public function clearEventCache(RxEvent $event)
    {
        if ($this->checkCacheStoreSupportsTags()) {
            \Log::info('Clearing RallyCross Event Cache', ['event' => $event->id]);
            $this->cache->tags($this->eventTag($event))->flush();
        }
        $this->clearChampionshipCache($event->championship);
    }

    /**
     * Clear the cache for a championship, and each entrants' driver cache
     * @param RxChampionship $championship
     */
    public function clearChampionshipCache(RxChampionship $championship)
    {
        if ($this->checkCacheStoreSupportsTags()) {
            \Log::info('Clearing RallyCross Championship Cache', ['championship' => $championship->id]);
            $this->cache->tags($this->championshipTag($championship))->flush();
        }
        foreach($championship->events AS $event) {
            foreach ($event->entrants AS $entrant) {
                $this->cache->forget($this->driverKey($entrant->driver));
            }
        }
    }

    private function checkCacheStoreSupportsTags()
    {
        return $this->cache->getStore() instanceof TaggableStore;
    }

}