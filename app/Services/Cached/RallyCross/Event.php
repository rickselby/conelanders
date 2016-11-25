<?php

namespace App\Services\Cached\RallyCross;

use App\Interfaces\RallyCross\EventInterface;
use App\Models\PointsSequence;
use App\Models\RallyCross\RxEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;

class Event implements EventInterface
{
    /**
     * @var Repository
     */
    protected $cache;
    
    /**
     * @var \App\Services\RallyCross\Event
     */
    protected $eventService;

    /**
     * @var string
     */
    protected $cacheKey = 'rallycross.event.';

    public function __construct(Repository $cache, \App\Services\RallyCross\Event $eventService)
    {
        $this->cache = $cache;
        $this->eventService = $eventService;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeShown(RxEvent $event)
    {
        return $this->eventService->canBeShown($event);
    }

    /**
     * {@inheritdoc}
     */
    public function currentUserInEvent(RxEvent $event)
    {
        return $this->eventService->currentUserInEvent($event);
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverIDs(RxEvent $event)
    {
        return $this->cache->tags(\RXCacheHandler::eventTag($event))->rememberForever($this->cacheKey.'driverIDs.'.$event->id, function() use ($event) {
            return $this->eventService->getDriverIDs($event);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeatResults(RxEvent $event)
    {
        return $this->eventService->hasHeatResults($event);
    }

    /**
     * {@inheritdoc}
     */
    public function areHeatsComplete(RxEvent $event)
    {
        return $this->eventService->areHeatsComplete($event);
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeatPoints(RxEvent $event)
    {
        return $this->eventService->hasHeatPoints($event);
    }

    /**
     * {@inheritdoc}
     */
    public function applyHeatsPointsSequence(RxEvent $event, PointsSequence $sequence)
    {
        return $this->eventService->applyHeatsPointsSequence($event, $sequence);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeatResults(RxEvent $event)
    {
        return $this->eventService->getHeatResults($event);
    }

    /**
     * {@inheritdoc}
     */
    public function setHeatsPoints(RxEvent $event, $points)
    {
        return $this->eventService->setHeatsPoints($event, $points);
    }

    /**
     * {@inheritdoc}
     */
    public function getPastNews(Carbon $start, Carbon $end)
    {
        // Don't cache for now; needs a lot more thought on how to cache / clear
        return $this->eventService->getPastNews($start, $end);
    }

    /**
     * {@inheritdoc}
     */
    public function getUpcomingNews(Carbon $start, Carbon $end)
    {
        // Don't cache for now; needs a lot more thought on how to cache / clear
        return $this->eventService->getUpcomingNews($start, $end);
    }
}
