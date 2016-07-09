<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;

use App\Http\Requests;
use Illuminate\Cache\TaggableStore;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('assetto-corsa.validateEvent')->only(['event']);
        $this->middleware('assetto-corsa.validateSession')->only(['lapChart']);
    }

    public function championship(AcChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant.driver.nation', 'entrants.driver.nation');
        $events = $championship->events()->get()->sortBy('time');
        return view('assetto-corsa.standings.championship')
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals(\ACResults::championship($championship)));
    }

    public function event($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        $event->load('sessions.entrants.championshipEntrant.driver.nation', 'sessions.entrants.laps', 'sessions.event');
        return view('assetto-corsa.standings.event')
            ->with('event', $event)
            ->with('points', \Positions::addEquals(\ACResults::eventSummary($event)));
    }

    public function lapChart($championshipStub, $raceStub, $sessionStub)
    {
        $session = \Request::get('session');
        $session->load('entrants.championshipEntrant.driver', 'entrants.laps');
        $content = \ACResults::lapChart($session);
        $response = \Response::make($content);
        $response->header('Content-type',  'image/svg+xml');
        return $response;
    }

}
