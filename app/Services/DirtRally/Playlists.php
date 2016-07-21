<?php

namespace App\Services\DirtRally;

use App\Interfaces\PlaylistsInterface;
use App\Models\DirtRally\DirtEvent;

class Playlists implements PlaylistsInterface
{
    public function getView()
    {
        return \View::make('dirt-rally.playlist', [
            'events' => DirtEvent::orderBy('closes', 'desc')->get()
        ])->render();
    }
}