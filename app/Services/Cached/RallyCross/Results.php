<?php

namespace App\Services\Cached\RallyCross;

use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;
use App\Models\Driver;
use App\Interfaces\RallyCross\ResultsInterface;
use Illuminate\Contracts\Cache\Repository;

class Results implements ResultsInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\RallyCross\Results
     */
    protected $resultsService;

    /**
     * @var string
     */
    protected $cacheKey = 'rallycross.results.';

    public function __construct(Repository $cache, \App\Services\RallyCross\Results $resultsService)
    {
        $this->cache = $cache;
        $this->resultsService = $resultsService;
    }

    /**
     * {@inheritdoc}
     */
    public function forDriver(Driver $driver)
    {
        // Find out the next time one of the drivers' championships will be updated
        $nextUpdate = false;
        foreach($driver->raceEntries AS $entry) {
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
        $key = \RXCacheHandler::driverKey($driver);
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
    public function forRace(RxSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\RXCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'forRace.'.$session->id, function() use ($session) {
            return $this->resultsService->forRace($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getWinner(RxEvent $event)
    {
        return $this->cache->tags(\RXCacheHandler::eventTag($event))->rememberForever($this->cacheKey.'winner.'.$event->id, function() use ($event) {
            return $this->resultsService->getWinner($event);
        });
    }

}