<?php

namespace App\Services\Cached\AssettoCorsa;

use App\Interfaces\AssettoCorsa\EventInterface;
use App\Models\AssettoCorsa\AcEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;

class Event implements EventInterface
{
    /**
     * @var Repository
     */
    protected $cache;
    
    /**
     * @var \App\Services\AssettoCorsa\Event
     */
    protected $eventService;

    /**
     * @var string
     */
    protected $cacheKey = 'event.';

    public function __construct(Repository $cache, \App\Services\AssettoCorsa\Event $eventService)
    {
        $this->cache = $cache;
        $this->eventService = $eventService;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeShown(AcEvent $event)
    {
        return $this->eventService->canBeShown($event);
    }

    /**
     * {@inheritdoc}
     */
    public function currentUserInEvent(AcEvent $event)
    {
        return $this->eventService->currentUserInEvent($event);
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverIDs(AcEvent $event)
    {
        return $this->cache->tags(\ACCacheHandler::eventTag($event))->rememberForever($this->cacheKey.'driverIDs.'.$event->id, function() use ($event) {
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
