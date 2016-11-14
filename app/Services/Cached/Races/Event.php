<?php

namespace App\Services\Cached\Races;

use App\Interfaces\Races\EventInterface;
use App\Models\Races\RacesEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;

class Event implements EventInterface
{
    /**
     * @var Repository
     */
    protected $cache;
    
    /**
     * @var \App\Services\Races\Event
     */
    protected $eventService;

    /**
     * @var string
     */
    protected $cacheKey = 'event.';

    public function __construct(Repository $cache, \App\Services\Races\Event $eventService)
    {
        $this->cache = $cache;
        $this->eventService = $eventService;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeShown(RacesEvent $event)
    {
        return $this->eventService->canBeShown($event);
    }

    /**
     * {@inheritdoc}
     */
    public function currentUserInEvent(RacesEvent $event)
    {
        return $this->eventService->currentUserInEvent($event);
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverIDs(RacesEvent $event)
    {
        return $this->cache->tags(\RacesCacheHandler::eventTag($event))->rememberForever($this->cacheKey.'driverIDs.'.$event->id, function() use ($event) {
            return $this->eventService->getDriverIDs($event);
        });
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
