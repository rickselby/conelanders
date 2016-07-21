<?php

namespace App\Listeners\DirtRally;

use App\Events\News\RequestCurrent;
use App\Events\News\RequestPast;
use App\Events\News\RequestUpcoming;
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
            RequestPast::class,
            'App\Listeners\DirtRally\News@getPastNews'
        );
        $events->listen(
            RequestUpcoming::class,
            'App\Listeners\DirtRally\News@getUpcomingNews'
        );
        $events->listen(
            RequestCurrent::class,
            'App\Listeners\DirtRally\News@getCurrent'
        );
    }

    public function getPastNews(RequestPast $event)
    {
        return $this->newsService->getPastNews($event->start, $event->end);
    }

    public function getUpcomingNews(RequestUpcoming $event)
    {
        return $this->newsService->getUpcomingNews($event->start, $event->end);
    }
    
    public function getCurrent(RequestCurrent $event)
    {
        return $this->newsService->getCurrentNews();
    }

}