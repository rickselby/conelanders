<?php

namespace App\Listeners\DirtRally;

use App\Events\Playlists\RequestPage;
use Illuminate\Events\Dispatcher;

class Playlists
{
    /**
     * @var \App\Services\DirtRally\Playlists
     */
    protected $playlistsService;

    public function __construct(\App\Services\DirtRally\Playlists $playlistsService)
    {
        $this->playlistsService = $playlistsService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RequestPage::class,
            'App\Listeners\DirtRally\Playlists@getPage'
        );
    }

    public function getPage()
    {
        return $this->playlistsService->getView();
    }

}