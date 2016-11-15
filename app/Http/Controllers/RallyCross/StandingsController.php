<?php

namespace App\Http\Controllers\Races;

use App\Http\Controllers\Controller;
use App\Models\Races\RacesCategory;
use App\Models\Races\RacesChampionship;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('races.validateEvent')->only(['event']);
        $this->middleware('races.validateSession')->only(['lapChart']);
    }

    public function drivers(RacesCategory $category, RacesChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant.driver.nation', 'entrants.driver.nation');
        $events = $championship->events()->get()->sortBy('time');
        return view('races.standings.drivers')
            ->with('category', $category)
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals(\RacesDriverStandings::championship($championship)));
    }

    public function constructors(RacesCategory $category, RacesChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant', 'events.championship', 'events.sessions.entrants.car');
        $events = $championship->events()->get()->sortBy('time');
        return view('races.standings.constructors')
            ->with('category', $category)
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals(\RacesConstructorStandings::championship($championship)));
    }

    public function teams(RacesCategory $category, RacesChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant.team', 'events.championship');
        $events = $championship->events()->get()->sortBy('time');
        return view('races.standings.teams')
            ->with('category', $category)
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals(\RacesTeamStandings::championship($championship)));
    }
}
