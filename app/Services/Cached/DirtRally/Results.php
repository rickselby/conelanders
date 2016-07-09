<?php

namespace App\Services\Cached\DirtRally;

use App\Interfaces\DirtRally\ResultsInterface;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtStage;
use App\Models\Driver;
use Illuminate\Contracts\Cache\Repository;

class Results implements ResultsInterface
{

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\DirtRally\Results
     */
    protected $resultsService;

    /**
     * @var string
     */
    protected $cacheKey = 'dr.results.';

    public function __construct(Repository $cache, \App\Services\DirtRally\Results $resultsService)
    {
        $this->cache = $cache;
        $this->resultsService = $resultsService;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventResults(DirtEvent $event)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\DirtRallyCacheHandler::eventTag($event))->rememberForever(
            $this->cacheKey.'event.'.$event->id, 
            function() use ($event) {
                return $this->resultsService->getEventResults($event);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getStageResults(DirtStage $stage)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\DirtRallyCacheHandler::stageTag($stage))->rememberForever(
            $this->cacheKey.'stage.'.$stage->id,
            function() use ($stage) {
                return $this->resultsService->getStageResults($stage);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function forDriver(Driver $driver)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->rememberForever(\DirtRallyCacheHandler::driverKey($driver), function() use ($driver) {
            return $this->resultsService->forDriver($driver);
        });
    }
}