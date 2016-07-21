<?php

namespace App\Listeners\AssettoCorsa;

use App\Events\Playlists\RequestPage;
use Illuminate\Events\Dispatcher;

class Playlists
{
    /**
     * @var \App\Services\AssettoCorsa\Playlists
     */
    protected $playlistsService;

    public function __construct(\App\Services\AssettoCorsa\Playlists $playlistsService)
    {
        $this->playlistsService = $playlistsService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RequestPage::class,
            'App\Listeners\AssettoCorsa\Playlists@getPage'
        );
    }

    public function getPage()
    {
        return $this->playlistsService->getView();
    }

}