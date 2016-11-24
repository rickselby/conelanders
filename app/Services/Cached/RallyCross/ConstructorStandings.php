<?php

namespace App\Services\Cached\RallyCross;

use App\Interfaces\RallyCross\StandingsInterface;
use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxEvent;
use Illuminate\Contracts\Cache\Repository;

class ConstructorStandings implements StandingsInterface
{
    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var StandingsInterface
     */
    protected $standingsService;

    /**
     * @var string
     */
    protected $cacheKey = 'rallycross.constructor-standings.';

    public function __construct(Repository $cache, \App\Services\RallyCross\ConstructorStandings $constructorStandingsService)
    {
        $this->cache = $cache;
        $this->standingsService = $constructorStandingsService;
    }

    /**
     * {@inheritdoc}
     */
    public function championship(RxChampionship $championship)
    {
        $tagStore = $this->cache->tags(\RXCacheHandler::championshipTag($championship));
        $key = $this->cacheKey . 'championship.' . $championship->id;
        $function = function () use ($championship) {
            return $this->standingsService->championship($championship);
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
    public function event(RxEvent $event)
    {
        return $this->eventCache(
            $event,
            $this->cacheKey . 'event.' . $event->id,
            function () use ($event) {
                return $this->standingsService->event($event);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function eventSummary(RxEvent $event)
    {
        return $this->eventCache(
            $event,
            $this->cacheKey . 'eventSummary.' . $event->id,
            function () use ($event) {
                return $this->standingsService->eventSummary($event);
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->standingsService->getOptions();
    }

    /**
     * Work out if / how we can cache event-related things
     * @param RxEvent $event
     * @param string $key Key for the tag
     * @param callable $function Function to run to get the event-related thing
     * @return mixed
     */
    private function eventCache(RxEvent $event, $key, callable $function)
    {
        $tagStore = $this->cache->tags(\RXCacheHandler::eventTag($event));

        // We don't cache for logged in users, unless the event is complete
        // I guess we could, but we'd want a separate cache item for each user, so that could get big quickly
        if (!\Auth::check() || $event->canBeReleased()) {

            // We can cache the event results permanently once the event is ready to be released...
            if ($event->canBeReleased()) {
                return $tagStore->rememberForever($key, $function);
            } else {
                // Otherwise we can cache until the next session release date
                return $tagStore->remember($key, $event->release, $function);
            }
        } else {
            // Not caching; just call the function
            return $function();
        }
    }
}