<?php

namespace App\Listeners\AssettoCorsa;

use App\Events\NewsRequest;
use App\Events\PastNewsRequest;
use App\Events\UpcomingNewsRequest;
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
            PastNewsRequest::class,
            'App\Listeners\AssettoCorsa\News@getPastNews'
        );
        $events->listen(
            UpcomingNewsRequest::class,
            'App\Listeners\AssettoCorsa\News@getUpcomingNews'
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

}