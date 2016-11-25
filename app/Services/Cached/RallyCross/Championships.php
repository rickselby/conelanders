<?php

namespace App\Services\Cached\RallyCross;

use App\Interfaces\RallyCross\ChampionshipInterface;
use App\Models\RallyCross\RxChampionship;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;

class Championships implements ChampionshipInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\RallyCross\Championships
     */
    protected $championshipsService;

    /**
     * @var string
     */
    protected $cacheKey = 'rallycross.championship.';

    public function __construct(Repository $cache, \App\Services\RallyCross\Championships $championshipsService)
    {
        $this->cache = $cache;
        $this->championshipsService = $championshipsService;
    }

    /**
     * @inheritdoc
     */
    public function shownBeforeRelease(RxChampionship $championship)
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
    public function cars(RxChampionship $championship)
    {
        return $this->cache->tags(\RXCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'cars.'.$championship->id,
            function() use ($championship) {
                return $this->championshipsService->cars($championship);
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function multipleCars(RxChampionship $championship)
    {
        return $this->cache->tags(\RXCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'multiple-cars.'.$championship->id,
            function() use ($championship) {
                return $this->championshipsService->multipleCars($championship);
            }
        );
    }
}
