<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\Championship;
use App\Models\Event;
use App\Models\Point;
use App\Models\PointsSystem;
use App\Models\Season;
use App\Models\Stage;

use App\Http\Requests;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('validateSeason')->only(['season']);
        $this->middleware('validateEvent')->only(['event']);
        $this->middleware('validateStage')->only(['stage']);
    }

    public function index()
    {
        return view('standings.index')
            ->with('systems', PointsSystem::all());

    }

    public function system(PointsSystem $system)
    {
        return view('standings.system')
            ->with('system', $system)
            ->with('championships', Championship::all()->sortBy('closes'));
    }

    public function championship(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('closes');
        return view('standings.championship')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DriverPoints::overall($system, $seasons));
    }

    public function overview(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('closes');
        return view('standings.overview')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DriverPoints::overview($system, $seasons));
    }

    public function season(PointsSystem $system, $championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver', 'events.positions.driver', 'championship']);
        return view('standings.season')
            ->with('system', $system)
            ->with('season', $season)
            ->with('points', \DriverPoints::forSeason($system, $season));
    }

    public function event(PointsSystem $system, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver', 'positions.driver']);
        return view('standings.event')
            ->with('system', $system)
            ->with('event', $event)
            ->with('points', \DriverPoints::forEvent($system, $event));
    }

    public function stage(PointsSystem $system, $championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        $stage->load(['event.season.championship']);
        return view('standings.stage')
            ->with('system', $system)
            ->with('stage', $stage)
            ->with('results', \Results::getStageResults($stage->id))
            ->with('points', \PointSequences::forSystem($system));
    }

}
