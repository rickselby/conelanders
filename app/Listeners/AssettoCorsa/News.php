<?php

namespace App\Listeners\AssettoCorsa;

use App\Events\NewsRequest;
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
            NewsRequest::class,
            'App\Listeners\AssettoCorsa\News@getNews'
        );
    }

    public function getNews(NewsRequest $event)
    {
        return $this->newsService->getNews();
    }

}