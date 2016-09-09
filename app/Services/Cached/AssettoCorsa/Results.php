<?php

namespace App\Services\Cached\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;
use App\Models\Driver;
use App\Interfaces\AssettoCorsa\ResultsInterface;
use Illuminate\Contracts\Cache\Repository;

class Results implements ResultsInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\AssettoCorsa\Results
     */
    protected $resultsService;

    /**
     * @var string
     */
    protected $cacheKey = 'ac.results.';

    public function __construct(Repository $cache, \App\Services\AssettoCorsa\Results $resultsService)
    {
        $this->cache = $cache;
        $this->resultsService = $resultsService;
    }

    /**
     * {@inheritdoc}
     */
    public function fastestLaps(AcSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\ACCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'fastestLaps.'.$session->id, function() use ($session) {
            return $this->resultsService->fastestLaps($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function forDriver(Driver $driver)
    {
        // Find out the next time one of the drivers' championships will be updated
        $nextUpdate = false;
        foreach($driver->acEntries AS $entry) {
            $champUpdate = $entry->championship->getNextUpdate();
            if ($champUpdate) {
                if ($nextUpdate) {
                    $nextUpdate = min($nextUpdate, $champUpdate);
                } else {
                    $nextUpdate = $champUpdate;
                }
            }
        }

        // Set up details for caching the info
        $key = \ACCacheHandler::driverKey($driver);
        $function = function() use ($driver) {
            return $this->resultsService->forDriver($driver);
        };

        // Cache forever if all championships are complete; or until the next championship update if not
        if (!$nextUpdate) {
            return $this->cache->rememberForever($key, $function);
        } else {
            return $this->cache->remember($key, $nextUpdate, $function);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function forRace(AcSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\ACCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'forRace.'.$session->id, function() use ($session) {
            return $this->resultsService->forRace($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function lapChart(AcSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\ACCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'lapChart.'.$session->id, function() use ($session) {
            return $this->resultsService->lapChart($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getWinner(AcEvent $event)
    {
        return $this->cache->tags(\ACCacheHandler::eventTag($event))->rememberForever($this->cacheKey.'winner.'.$event->id, function() use ($event) {
            return $this->resultsService->getWinner($event);
        });
    }

}