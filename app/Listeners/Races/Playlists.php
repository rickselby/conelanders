<?php

namespace App\Listeners\Races;

use App\Events\Playlists\RequestPage;
use Illuminate\Events\Dispatcher;

class Playlists
{
    /**
     * @var \App\Services\Races\Playlists
     */
    protected $playlistsService;

    public function __construct(\App\Services\Races\Playlists $playlistsService)
    {
        $this->playlistsService = $playlistsService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RequestPage::class,
            'App\Listeners\Races\Playlists@getPage'
        );
    }

    public function getPage()
    {
        return $this->playlistsService->getView();
    }

}