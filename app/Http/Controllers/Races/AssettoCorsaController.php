<?php

namespace App\Http\Controllers\Races;

use App\Http\Controllers\Controller;
use App\Models\Races\RacesChampionship;

class AssettoCorsaController extends Controller
{
    public function index()
    {
        return view('races.index')
            ->with('championships', RacesChampionship::with(
                'events.sessions.entrants.championshipEntrant.driver.nation',
                'events.sessions.playlist',
                'events.sessions.event')->get()->sortByDesc('ends'));
    }

}