<?php

namespace App\Http\Controllers\RallyCross;

use App\Http\Controllers\Controller;
use App\Models\RallyCross\RxChampionship;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('races.validateEvent')->only(['event']);
    }

    public function drivers(RxChampionship $championship)
    {
        $championship->load('events.sessions.entrants', 'events.entrants.driver.nation');
        return view('rallycross.standings.drivers')
            ->with('championship', $championship)
            ->with('points', \Positions::addEquals(\RXDriverStandings::championship($championship)));
    }

    public function constructors(RxChampionship $championship)
    {
        $championship->load(
            'events.sessions.entrants',
            'events.championship',
            'events.entrants.car'
        );
        $events = $championship->events;
        return view('rallycross.standings.constructors')
            ->with('championship', $championship)
            ->with('points', \Positions::addEquals(\RXConstructorStandings::championship($championship)));
    }

}
