<?php

namespace App\Listeners\DirtRally;

use App\Events\NewsRequest;
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
            NewsRequest::class,
            'App\Listeners\DirtRally\News@getNews'
        );
    }

    public function getNews(NewsRequest $event)
    {
        return $this->newsService->getNews();
    }

}