<?php

namespace App\Http\Controllers\RallyCross;

use App\Http\Controllers\Controller;
use App\Models\RallyCross\RxChampionship;

class ResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('rallycross.validateEvent')->only(['event']);
    }

    public function index()
    {
        return view('rallycross.index')
            ->with('championships', RxChampionship::with(
                'events.sessions.entrants.eventEntrant.driver.nation',
                'events.sessions.event')->get()->sortByDesc('ends'));
    }

    public function championship(RxChampionship $championship)
    {
        $championship->load([
            'events.sessions.entrants.eventEntrant.driver.nation',
            'events.sessions.event',
        ]);

        return view('rallycross.results.championship')
            ->with('championship', $championship);
    }

    public function event($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        $event->load(
            'sessions.entrants.eventEntrant.car',
            'sessions.entrants.eventEntrant.driver.nation',
            'sessions.event.championship.events.entrants.car',
            'heatResult.entrant.driver.nation',
            'heatResult.entrant.car',
            'entrants.driver.nation'
        );
        return view('rallycross.results.event')
            ->with('event', $event);
    }

}
