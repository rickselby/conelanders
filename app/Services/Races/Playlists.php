<?php

namespace App\Services\Races;

use App\Interfaces\PlaylistsInterface;
use App\Models\Races\RacesSession;

class Playlists implements PlaylistsInterface
{
    public function getView()
    {
        return \View::make('races.playlist', [
            'sessions' => RacesSession::whereNotNull('release')->where('type', RacesSession::TYPE_RACE)
                ->orderBy('release', 'desc')->orderBy('order', 'desc')->get()
        ])->render();
    }
}