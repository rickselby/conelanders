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

class NationStandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('validateSeason')->only(['season']);
        $this->middleware('validateEvent')->only(['event']);
        $this->middleware('validateStage')->only(['stage']);
    }

    public function index()
    {
        return view('nationstandings.index')
            ->with('systems', PointsSystem::all());

    }

    public function system(PointsSystem $system)
    {
        return view('nationstandings.system')
            ->with('system', $system)
            ->with('championships', Championship::all()->sortBy('closes'));
    }

    public function championship(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('nationstandings.championship')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \NationPoints::overall($system, $seasons));
    }

    public function overview(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('nationstandings.overview')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \NationPoints::overview($system, $seasons));
    }

    public function season(PointsSystem $system, $championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver.nation', 'events.positions.driver.nation', 'championship']);
        return view('nationstandings.season')
            ->with('system', $system)
            ->with('season', $season)
            ->with('points', \NationPoints::forSeason($system, $season));
    }

    public function event(PointsSystem $system, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver.nation', 'positions.driver.nation']);
        return view('nationstandings.event')
            ->with('system', $system)
            ->with('event', $event)
            ->with('points', \NationPoints::forEvent($system, $event));
    }
}
