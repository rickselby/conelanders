<?php

namespace App\Services\Cached\DirtRally;


use App\Interfaces\DirtRally\DriverPointsInterface;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;
use Illuminate\Contracts\Cache\Repository;

class DriverPoints implements DriverPointsInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\DirtRally\DriverPoints
     */
    protected $driverPointsService;

    /**
     * @var string
     */
    protected $cacheKey = 'dr.driverpoints.';

    public function __construct(Repository $cache, \App\Services\DirtRally\DriverPoints $driverPointsService)
    {
        $this->cache = $cache;
        $this->driverPointsService = $driverPointsService;
    }

    /**
     * {@inheritdoc}
     */
    public function forEvent(DirtEvent $event)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::eventTag($event))->rememberForever(
            $this->cacheKey.'event.'.$event->id,
            function() use ($event) {
                return $this->driverPointsService->forEvent($event);
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
                return $this->driverPointsService->forSeason($season);
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
                return $this->driverPointsService->overview($championship);
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
                return $this->driverPointsService->overall($championship);
            }
        );
    }
}