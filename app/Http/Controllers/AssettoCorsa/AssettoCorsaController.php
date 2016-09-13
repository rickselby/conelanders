<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;

class AssettoCorsaController extends Controller
{
    public function index()
    {
        return view('assetto-corsa.index')
            ->with('championships', AcChampionship::with(
                'events.sessions.entrants.championshipEntrant.driver.nation',
                'events.sessions.playlist',
                'events.sessions.event')->get()->sortByDesc('ends'));
    }

}