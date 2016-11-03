<?php

namespace App\Services\Cached\DirtRally;

use App\Interfaces\DirtRally\NationPointsInterface;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;
use App\Models\Nation;
use Illuminate\Contracts\Cache\Repository;

class NationPoints implements NationPointsInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\DirtRally\NationPoints
     */
    protected $nationPointsService;

    /**
     * @var string
     */
    protected $cacheKey = 'dr.nationpoints.';

    public function __construct(Repository $cache, \App\Services\DirtRally\NationPoints $nationPointsService)
    {
        $this->cache = $cache;
        $this->nationPointsService = $nationPointsService;
    }

    /**
     * {@inheritdoc}
     */
    public function forEvent(DirtEvent $event)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::eventTag($event))->rememberForever(
            $this->cacheKey.'event.'.$event->id,
            function() use ($event) {
                return $this->nationPointsService->forEvent($event);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function forSeason(DirtSeason $season)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::seasonTag($season))->rememberForever(
            $this->cacheKey.'season.'.$season->id,
            function() use ($season) {
                return $this->nationPointsService->forSeason($season);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function overview(DirtChampionship $championship)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'overview.'.$championship->id,
            function() use ($championship) {
                return $this->nationPointsService->overview($championship);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function overall(DirtChampionship $championship)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'overall.'.$championship->id,
            function() use ($championship) {
                return $this->nationPointsService->overall($championship);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function details(DirtEvent $event, Nation $nation)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::eventTag($event))->rememberForever(
            $this->cacheKey.'detail.'.$event->id.'-'.$nation->id,
            function() use ($event, $nation) {
                return $this->nationPointsService->details($event, $nation);
            }
        );
    }    
}