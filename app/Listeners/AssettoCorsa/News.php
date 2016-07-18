<?php

namespace App\Listeners\AssettoCorsa;

use App\Events\News\RequestPast;
use App\Events\News\RequestUpcoming;
use Illuminate\Events\Dispatcher;

class News
{
    /**
     * @var \App\Services\AssettoCorsa\News
     */
    protected $newsService;

    public function __construct(\App\Services\AssettoCorsa\News $newsService)
    {
        $this->newsService = $newsService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RequestPast::class,
            'App\Listeners\AssettoCorsa\News@getPastNews'
        );
        $events->listen(
            RequestUpcoming::class,
            'App\Listeners\AssettoCorsa\News@getUpcomingNews'
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

}