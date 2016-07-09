<?php

namespace App\Services\Cached\DirtRally;

use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;
use App\Models\DirtRally\DirtStage;
use App\Models\Driver;
use App\Services\DirtRally\Championships;
use Illuminate\Contracts\Cache\Repository;

class Handler
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var Championships
     */
    protected $championships;

    protected $cacheKey = 'dr.tag.';

    public function __construct(Repository $cache, Championships $championships)
    {
        $this->cache = $cache;
        $this->championships = $championships;
    }

    /**
     * Get the tag for a session
     * @param DirtStage $stage
     * @return string
     */
    public function stageTag(DirtStage $stage)
    {
        return $this->cacheKey.'stage.'.$stage->id;
    }

    /**
     * Get the tag for an event
     * @param DirtEvent $event
     * @return string
     */
    public function eventTag(DirtEvent $event)
    {
        return $this->cacheKey.'event.'.$event->id;
    }

    /**
     * Get the tag for a season
     * @param DirtSeason $season
     * @return string
     */
    public function seasonTag(DirtSeason $season)
    {
        return $this->cacheKey.'season.'.$season->id;
    }

    /**
     * Get the tag for an championship
     * @param DirtChampionship $championship
     * @return string
     */
    public function championshipTag(DirtChampionship $championship)
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
        return $this->driverKeyFromID($driver->id);
    }

    /**
     * Get the tag for a drivers' results
     * @param Driver $driver
     * @return string
     */
    private function driverKeyFromID($driverID)
    {
        return $this->cacheKey.'driver.'.$driverID;
    }

    /**
     * Clear the cache for a session, and clear parents
     * @param DirtStage $stage
     */
    public function clearStageCache(DirtStage $stage, $propogate = true)
    {
        $this->cache->tags($this->stageTag($stage))->flush();
        if ($propogate) {
            $this->clearEventCache($stage->event);
        }
    }

    /**
     * Clear the cache for an event, and clear parents
     * @param DirtEvent $event
     */
    public function clearEventCache(DirtEvent $event, $propogate = true)
    {
        $this->cache->tags($this->eventTag($event))->flush();
        if ($propogate) {
            $this->clearSeasonCache($event->season);
        }
    }

    /**
     * Clear the cache for a season, and clear parents
     * @param DirtSeason $season
     */
    public function clearSeasonCache(DirtSeason $season)
    {
        $this->cache->tags($this->seasonTag($season))->flush();
        $this->clearChampionshipCache($season->championship);
    }
    
    /**
     * Clear the cache for a championship, and each entrants' driver cache
     * @param DirtChampionship $championship
     */
    public function clearChampionshipCache(DirtChampionship $championship)
    {
        $this->cache->tags($this->championshipTag($championship))->flush();

        // We need to clear cache for drivers that were in this championship...
        foreach($this->championships->getDriversFor($championship) AS $driverID) {
            $this->cache->forget($this->driverKeyFromID($driverID));
        }
    }

    /**
     * Clear cache for all stages of an event
     * @param DirtEvent $event
     */
    public function clearEventStages(DirtEvent $event)
    {
        foreach($event->stages AS $stage) {
            $this->clearStageCache($stage, false);
        }
        $this->clearEventCache($event, false);
        // Only need to clear season / championship if the event is done
        if ($event->isComplete()) {
            $this->clearSeasonCache($event->season);
        }
    }

}