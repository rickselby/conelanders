<?php

namespace App\Services\Cached\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;
use App\Models\Driver;
use Illuminate\Contracts\Cache\Repository;

class Handler
{
    /**
     * @var Repository
     */
    protected $cache;

    protected $cacheKey = 'ac.tag.';

    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Get the tag for a session
     * @param AcSession $session
     * @return string
     */
    public function sessionTag(AcSession $session)
    {
        return $this->cacheKey.'session.'.$session->id;
    }

    /**
     * Get the tag for an event
     * @param AcEvent $event
     * @return string
     */
    public function eventTag(AcEvent $event)
    {
        return $this->cacheKey.'event.'.$event->id;
    }

    /**
     * Get the tag for an championship
     * @param AcChampionship $championship
     * @return string
     */
    public function championshipTag(AcChampionship $championship)
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
     * @param AcSession $session
     */
    public function clearSessionCache(AcSession $session)
    {
        \Log::info('Clearing Assetto Corsa Session Cache', ['session' => $session->id]);
        $this->cache->tags($this->sessionTag($session))->flush();
        $this->clearEventCache($session->event);
    }

    /**
     * Clear the cache for an event, and clear parents
     * @param AcEvent $event
     */
    public function clearEventCache(AcEvent $event)
    {
        \Log::info('Clearing Assetto Corsa Event Cache', ['event' => $event->id]);
        $this->cache->tags($this->eventTag($event))->flush();
        $this->clearChampionshipCache($event->championship);
    }

    /**
     * Clear the cache for a championship, and each entrants' driver cache
     * @param AcChampionship $championship
     */
    public function clearChampionshipCache(AcChampionship $championship)
    {
        \Log::info('Clearing Assetto Corsa Championship Cache', ['championship' => $championship->id]);
        $this->cache->tags($this->championshipTag($championship))->flush();
        foreach($championship->entrants AS $entrant) {
            $this->cache->forget($this->driverKey($entrant->driver));
        }
    }

}