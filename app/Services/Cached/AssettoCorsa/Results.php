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
    public function championship(AcChampionship $championship)
    {
        $tagStore = $this->cache->tags(\ACCacheHandler::championshipTag($championship));
        $key = $this->cacheKey . 'championship.' . $championship->id;
        $function = function () use ($championship) {
            return $this->resultsService->championship($championship);
        };

        // We don't cache for logged in users, unless the championship is complete
        // I guess we could, but we'd want a separate cache item for each user, so that could get big quickly
        if (!\Auth::check() || $championship->isComplete()) {

            // We can cache the event results permanently once the event is ready to be released...
            if ($championship->isComplete()) {
                return $tagStore->rememberForever($key, $function);
            } else {
                // Otherwise we can cache until the next session release date
                return $tagStore->remember($key, $championship->getNextUpdate(), $function);
            }
        } else {
            // Not caching; just call the function
            return $function();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function event(AcEvent $event)
    {
        return $this->eventCache(
            $event,
            $this->cacheKey . 'event.' . $event->id,
            function () use ($event) {
                return $this->resultsService->event($event);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function eventSummary(AcEvent $event)
    {
        return $this->eventCache(
            $event,
            $this->cacheKey . 'eventSummary.' . $event->id,
            function () use ($event) {
                return $this->resultsService->eventSummary($event);
            }
        );
    }

    /**
     * Work out if / how we can cache event-related things
     * @param AcEvent $event
     * @param string $key Key for the tag
     * @param callable $function Function to run to get the event-related thing
     * @return mixed
     */
    private function eventCache(AcEvent $event, $key, callable $function)
    {
        $tagStore = $this->cache->tags(\ACCacheHandler::eventTag($event));

        // We don't cache for logged in users, unless the event is complete
        // I guess we could, but we'd want a separate cache item for each user, so that could get big quickly
        if (!\Auth::check() || $event->canBeReleased()) {

            // We can cache the event results permanently once the event is ready to be released...
            if ($event->canBeReleased()) {
                return $tagStore->rememberForever($key, $function);
            } else {
                // Otherwise we can cache until the next session release date
                return $tagStore->remember($key, $event->getNextUpdate(), $function);
            }
        } else {
            // Not caching; just call the function
            return $function();
        }
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

}