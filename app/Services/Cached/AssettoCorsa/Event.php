<?php

namespace App\Services\Cached\AssettoCorsa;

use App\Interfaces\AssettoCorsa\EventInterface;
use App\Models\AssettoCorsa\AcEvent;
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
}
