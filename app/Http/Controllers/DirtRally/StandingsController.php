<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\Championship;
use App\Models\DirtRally\Event;
use App\Models\DirtRally\Point;
use App\Models\DirtRally\PointsSystem;
use App\Models\DirtRally\Season;
use App\Models\DirtRally\Stage;

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
            ->with('systems', PointsSystem::all());

    }

    public function system(PointsSystem $system)
    {
        return view('dirt-rally.standings.system')
            ->with('system', $system)
            ->with('championships', Championship::all()->sortBy('closes'));
    }

    public function championship(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('closes');
        return view('dirt-rally.standings.championship')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DirtRallyDriverPoints::overall($system, $seasons));
    }

    public function overview(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('closes');
        return view('dirt-rally.standings.overview')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DirtRallyDriverPoints::overview($system, $seasons));
    }

    public function season(PointsSystem $system, $championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver', 'events.positions.driver', 'championship']);
        return view('dirt-rally.standings.season')
            ->with('system', $system)
            ->with('season', $season)
            ->with('points', \DirtRallyDriverPoints::forSeason($system, $season));
    }

    public function event(PointsSystem $system, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver', 'positions.driver']);
        return view('dirt-rally.standings.event')
            ->with('system', $system)
            ->with('event', $event)
            ->with('points', \DirtRallyDriverPoints::forEvent($system, $event));
    }

    public function stage(PointsSystem $system, $championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        $stage->load(['event.season.championship']);
        return view('dirt-rally.standings.stage')
            ->with('system', $system)
            ->with('stage', $stage)
            ->with('results', \DirtRallyResults::getStageResults($stage->id))
            ->with('points', \DirtRallyPointSequences::forSystem($system));
    }

}
