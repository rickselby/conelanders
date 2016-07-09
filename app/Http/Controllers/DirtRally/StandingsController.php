<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\DirtChampionship;

use App\Http\Requests;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('dirt-rally.validateSeason')->only(['season']);
        $this->middleware('dirt-rally.validateEvent')->only(['event']);
        $this->middleware('dirt-rally.validateStage')->only(['stage']);
    }

    public function championship(DirtChampionship $championship)
    {
        return view('dirt-rally.standings.championship')
            ->with('championship', $championship)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::overall($championship)));
    }

    public function overview(DirtChampionship $championship)
    {
        return view('dirt-rally.standings.overview')
            ->with('championship', $championship)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::overview($championship)));
    }

    public function season($championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver', 'events.positions.driver', 'championship']);
        return view('dirt-rally.standings.season')
            ->with('season', $season)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::forSeason($season)));
    }

    public function event($championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver', 'positions.driver']);
        return view('dirt-rally.standings.event')
            ->with('event', $event)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::forEvent($event)));
    }

    public function stage($championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        $stage->load(['event.season.championship']);
        return view('dirt-rally.standings.stage')
            ->with('stage', $stage)
            ->with('results', \Positions::addEquals(\DirtRallyResults::getStageResults($stage)))
            ->with('points', \PointSequences::get($stage->event->season->championship->stagePointsSequence));
    }

}
