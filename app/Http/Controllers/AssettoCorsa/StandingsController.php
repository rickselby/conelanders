<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;

use App\Http\Requests;
use App\Services\AssettoCorsa\DriverPoints;
use App\Services\AssettoCorsa\Results;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('assetto-corsa.validateEvent')->only(['event']);
        $this->middleware('assetto-corsa.validateSession')->only(['lapChart']);
    }

    public function championship(AcChampionship $championship, Results $resultsService)
    {
        $championship->load('events.sessions.entrants.championshipEntrant.driver.nation', 'entrants.driver.nation');
        $events = $championship->events()->get()->sortBy('time');
        return view('assetto-corsa.standings.championship')
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals($resultsService->championship($championship)));
    }

    public function event($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        return view('assetto-corsa.standings.event')
            ->with('event', $event);
    }

    public function lapChart($championshipStub, $raceStub, $sessionStub, Results $resultsService)
    {
        $session = \Request::get('session');
        $content = $resultsService->lapChart($session);
        $response = \Response::make($content);
        $response->header('Content-type',  'image/svg+xml');
        return $response;
    }

}
