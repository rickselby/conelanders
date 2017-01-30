<?php

namespace App\Services\Cached\AcHotlap;

use App\Models\AcHotlap\AcHotlapSession;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;
use App\Models\Driver;
use App\Interfaces\AcHotlap\ResultsInterface;
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
    protected $cacheKey = 'ac.hotlaps.results.';

    public function __construct(Repository $cache, \App\Services\AcHotlap\Results $resultsService)
    {
        $this->cache = $cache;
        $this->resultsService = $resultsService;
    }

    /**
     * {@inheritdoc}
     */
    public function forSession(AcHotlapSession $session)
    {
        // This doesn't rely on "is user in this session" or "is session released yet" so it can be cached permanently
        // (any updates to the session will clear the cache)
        return $this->cache->tags(\AcHotlapCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'forRace.'.$session->id, function() use ($session) {
            return $this->resultsService->forSession($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getWinners(AcHotlapSession $session)
    {
        return $this->cache->tags(\AcHotlapCacheHandler::sessionTag($session))->rememberForever($this->cacheKey.'winner.'.$session->id, function() use ($session) {
            return $this->resultsService->getWinner($session);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function withWinners()
    {
        return $this->cache->rememberForever(\AcHotlapCacheHandler::summaryName(), function() {
            return $this->resultsService->withWinners();
        });
    }

}