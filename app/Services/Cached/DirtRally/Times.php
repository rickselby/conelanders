<?php

namespace App\Services\Cached\DirtRally;

use App\Interfaces\DirtRally\TimesInterface;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;
use Illuminate\Contracts\Cache\Repository;

class Times implements TimesInterface
{

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\DirtRally\Times
     */
    protected $timesService;

    /**
     * @var string
     */
    protected $cacheKey = 'dr.times.';

    public function __construct(Repository $cache, \App\Services\DirtRally\Times $timesService)
    {
        $this->cache = $cache;
        $this->timesService = $timesService;
    }

    /**
     * {@inheritdoc}
     */
    public function forEvent(DirtEvent $event)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::eventTag($event))->rememberForever(
            $this->cacheKey.'event.'.$event->id,
            function() use ($event) {
                return $this->timesService->forEvent($event);
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
                return $this->timesService->forSeason($season);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function overall(DirtChampionship $championship)
    {
        return $this->cache->tags(\DirtRallyCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'championship.'.$championship->id,
            function() use ($championship) {
                return $this->timesService->overall($championship);
            }
        );
    }
}