<?php

namespace App\Listeners\DirtRally;

use App\Events\CurrentNewsRequest;
use App\Events\PastNewsRequest;
use App\Events\UpcomingNewsRequest;
use Illuminate\Events\Dispatcher;

class News
{
    /**
     * @var \App\Services\DirtRally\News
     */
    protected $newsService;

    public function __construct(\App\Services\DirtRally\News $newsService)
    {
        $this->newsService = $newsService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            PastNewsRequest::class,
            'App\Listeners\DirtRally\News@getPastNews'
        );
        $events->listen(
            UpcomingNewsRequest::class,
            'App\Listeners\DirtRally\News@getUpcomingNews'
        );
        $events->listen(
            CurrentNewsRequest::class,
            'App\Listeners\DirtRally\News@getCurrent'
        );
    }

    public function getPastNews(PastNewsRequest $event)
    {
        return $this->newsService->getPastNews($event->start, $event->end);
    }

    public function getUpcomingNews(UpcomingNewsRequest $event)
    {
        return $this->newsService->getUpcomingNews($event->start, $event->end);
    }
    
    public function getCurrent(CurrentNewsRequest $event)
    {
        return $this->newsService->getCurrentNews();
    }

}