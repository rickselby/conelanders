<?php

namespace App\Services\Cached\AssettoCorsa;

use App\Interfaces\AssettoCorsa\StandingsInterface;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use Illuminate\Contracts\Cache\Repository;

class DriverStandings implements StandingsInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var \App\Services\AssettoCorsa\DriverStandings
     */
    protected $driverStandingsService;

    /**
     * @var string
     */
    protected $cacheKey = 'ac.driver-standings.';

    public function __construct(Repository $cache, \App\Services\AssettoCorsa\DriverStandings $driverStandingsService)
    {
        $this->cache = $cache;
        $this->driverStandingsService = $driverStandingsService;
    }

    /**
     * {@inheritdoc}
     */
    public function championship(AcChampionship $championship)
    {
        $tagStore = $this->cache->tags(\ACCacheHandler::championshipTag($championship));
        $key = $this->cacheKey . 'championship.' . $championship->id;
        $function = function () use ($championship) {
            return $this->driverStandingsService->championship($championship);
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
                return $this->driverStandingsService->event($event);
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
                return $this->driverStandingsService->eventSummary($event);
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
}