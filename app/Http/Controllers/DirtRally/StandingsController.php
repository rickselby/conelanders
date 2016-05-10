<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtPointsSystem;

use App\Http\Requests;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('dirt-rally.validateSeason')->only(['season']);
        $this->middleware('dirt-rally.validateEvent')->only(['event']);
        $this->middleware('dirt-rally.validateStage')->only(['stage']);
    }

    public function index()
    {
        return view('dirt-rally.standings.index')
            ->with('systems', DirtPointsSystem::all());

    }

    public function system(DirtPointsSystem $system)
    {
        return view('dirt-rally.standings.system')
            ->with('system', $system)
            ->with('championships', DirtChampionship::all()->sortBy('closes'));
    }

    public function championship(DirtPointsSystem $system, DirtChampionship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('closes');
        return view('dirt-rally.standings.championship')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::overall($system, $seasons)));
    }

    public function overview(DirtPointsSystem $system, DirtChampionship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('closes');
        return view('dirt-rally.standings.overview')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::overview($system, $seasons)));
    }

    public function season(DirtPointsSystem $system, $championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver', 'events.positions.driver', 'championship']);
        return view('dirt-rally.standings.season')
            ->with('system', $system)
            ->with('season', $season)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::forSeason($system, $season)));
    }

    public function event(DirtPointsSystem $system, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver', 'positions.driver']);
        return view('dirt-rally.standings.event')
            ->with('system', $system)
            ->with('event', $event)
            ->with('points', \Positions::addEquals(\DirtRallyDriverPoints::forEvent($system, $event)));
    }

    public function stage(DirtPointsSystem $system, $championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        $stage->load(['event.season.championship']);
        return view('dirt-rally.standings.stage')
            ->with('system', $system)
            ->with('stage', $stage)
            ->with('results', \Positions::addEquals(\DirtRallyResults::getStageResults($stage->id)))
            ->with('points', \DirtRallyPointSequences::forSystem($system));
    }

}
