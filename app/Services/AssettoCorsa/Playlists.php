<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\PlaylistsInterface;
use App\Models\AssettoCorsa\AcSession;

class Playlists implements PlaylistsInterface
{
    public function getView()
    {
        return \View::make('assetto-corsa.playlist', [
            'sessions' => AcSession::orderBy('release', 'desc')->orderBy('order', 'desc')->get()
        ])->render();
    }
}