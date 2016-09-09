<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;

class ResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('assetto-corsa.validateEvent')->only(['event']);
        $this->middleware('assetto-corsa.validateSession')->only(['lapChart']);
    }

    public function championship(AcChampionship $championship)
    {
        $championship->load([
            'events.sessions.entrants.championshipEntrant.driver.nation',
            'events.sessions.playlist',
            'events.sessions.event'
        ]);

        return view('assetto-corsa.results.championship')
            ->with('championship', $championship);
    }

    public function event($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        $event->load('sessions.entrants.championshipEntrant.driver.nation', 'sessions.event');
        return view('assetto-corsa.results.event')
            ->with('event', $event);
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
