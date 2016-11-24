<?php

namespace App\Services\Cached\Races;

use App\Interfaces\Races\ChampionshipInterface;
use App\Models\Races\RacesChampionship;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;

class Championships implements ChampionshipInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\Races\Championships
     */
    protected $championshipsService;

    /**
     * @var string
     */
    protected $cacheKey = 'races.championship.';

    public function __construct(Repository $cache, \App\Services\Races\Championships $championshipsService)
    {
        $this->cache = $cache;
        $this->championshipsService = $championshipsService;
    }

    /**
     * @inheritdoc
     */
    public function shownBeforeRelease(RacesChampionship $championship)
    {
        return $this->championshipsService->shownBeforeRelease($championship);
    }

    /**
     * @inheritdoc
     */
    public function getPastNews(Carbon $start, Carbon $end)
    {
        return $this->championshipsService->getPastNews($start, $end);
    }

    /**
     * @inheritdoc
     */
    public function cars(RacesChampionship $championship)
    {
        return $this->cache->tags(\RacesCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'cars.'.$championship->id,
            function() use ($championship) {
                return $this->championshipsService->cars($championship);
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function multipleCars(RacesChampionship $championship)
    {
        return $this->cache->tags(\RacesCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'multiple-cars.'.$championship->id,
            function() use ($championship) {
                return $this->championshipsService->multipleCars($championship);
            }
        );
    }
}
