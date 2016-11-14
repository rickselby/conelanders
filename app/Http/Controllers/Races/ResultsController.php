<?php

namespace App\Http\Controllers\Races;

use App\Http\Controllers\Controller;
use App\Models\Races\RacesChampionship;

class ResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('races.validateEvent')->only(['event']);
        $this->middleware('races.validateSession')->only(['lapChart']);
    }

    public function championship(RacesChampionship $championship)
    {
        $championship->load([
            'events.sessions.entrants.championshipEntrant.driver.nation',
            'events.sessions.entrants.championshipEntrant.team',
            'events.sessions.entrants.championshipEntrant.car',
            'events.sessions.playlist',
            'events.sessions.event',
            'teams.entrants.driver.nation',
        ]);

        return view('races.results.championship')
            ->with('championship', $championship);
    }

    public function event($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        $event->load(
            'sessions.entrants.car',
            'sessions.entrants.championshipEntrant.driver.nation',
            'sessions.entrants.championshipEntrant.team',
            'sessions.event'
        );
        return view('races.results.event')
            ->with('event', $event);
    }

    public function lapChart($championshipStub, $raceStub, $sessionStub)
    {
        $session = \Request::get('session');
        $session->load('entrants.championshipEntrant.driver', 'entrants.laps');
        $content = \RacesResults::lapChart($session);
        $response = \Response::make($content);
        $response->header('Content-type',  'image/svg+xml');
        return $response;
    }

}
