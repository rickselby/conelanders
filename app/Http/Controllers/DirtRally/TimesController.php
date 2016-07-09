<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\DirtChampionship;

use App\Http\Requests;

class TimesController extends Controller
{
    public function __construct()
    {
        $this->middleware('dirt-rally.validateSeason')->only(['season']);
        $this->middleware('dirt-rally.validateEvent')->only(['event']);
        $this->middleware('dirt-rally.validateStage')->only(['stage']);
    }

    public function championship(DirtChampionship $championship)
    {
        return view('dirt-rally.times.championship')
            ->with('championship', $championship)
            ->with('times', \Positions::addEquals(\DirtRallyTimes::overall($championship)));
    }

    public function season($championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver', 'events.positions.driver']);
        return view('dirt-rally.times.season')
            ->with('season', $season)
            ->with('times', \Positions::addEquals(\DirtRallyTimes::forSeason($season)['times']));
    }

    public function event($championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season', 'stages.results.driver', 'positions.driver']);
        return view('dirt-rally.times.event')
            ->with('event', $event)
            ->with('times', \Positions::addEquals(\DirtRallyTimes::forEvent($event)['times']));
    }

    public function stage($championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        $stage->load('event.season');
        return view('dirt-rally.times.stage')
            ->with('stage', $stage)
            ->with('results', \Positions::addEquals(\DirtRallyResults::getStageResults($stage)));
    }

}
