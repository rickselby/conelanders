<?php

namespace App\Services\Cached\AssettoCorsa;

use App\Interfaces\AssettoCorsa\ChampionshipInterface;
use App\Models\AssettoCorsa\AcChampionship;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;

class Championships implements ChampionshipInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\AssettoCorsa\Championships
     */
    protected $championshipsService;

    /**
     * @var string
     */
    protected $cacheKey = 'championship.';

    public function __construct(Repository $cache, \App\Services\AssettoCorsa\Championships $championshipsService)
    {
        $this->cache = $cache;
        $this->championshipsService = $championshipsService;
    }

    /**
     * @inheritdoc
     */
    public function shownBeforeRelease(AcChampionship $championship)
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
    public function cars(AcChampionship $championship)
    {
        return $this->cache->tags(\ACCacheHandler::championshipTag($championship))->rememberForever(
            $this->cacheKey.'cars.'.$championship->id,
            function() use ($championship) {
                return $this->championshipsService->cars($championship);
            }
        );
    }
}
