<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtPointsSystem;
use App\Http\Requests;

class NationStandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('dirt-rally.validateSeason')->only(['season']);
        $this->middleware('dirt-rally.validateEvent')->only(['event']);
        $this->middleware('dirt-rally.validateStage')->only(['stage']);
    }

    public function index()
    {
        return view('dirt-rally.nationstandings.index')
            ->with('systems', DirtPointsSystem::all());

    }

    public function system(DirtPointsSystem $system)
    {
        return view('dirt-rally.nationstandings.system')
            ->with('system', $system)
            ->with('championships', DirtChampionship::all()->sortBy('closes'));
    }

    public function championship(DirtPointsSystem $system, DirtChampionship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('dirt-rally.nationstandings.championship')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DirtRallyPositions::addEquals(\DirtRallyNationPoints::overall($system, $seasons)));
    }

    public function overview(DirtPointsSystem $system, DirtChampionship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('dirt-rally.nationstandings.overview')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DirtRallyPositions::addEquals(\DirtRallyNationPoints::overview($system, $seasons)));
    }

    public function season(DirtPointsSystem $system, $championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver.nation', 'events.positions.driver.nation', 'championship']);
        return view('dirt-rally.nationstandings.season')
            ->with('system', $system)
            ->with('season', $season)
            ->with('points', \DirtRallyPositions::addEquals(\DirtRallyNationPoints::forSeason($system, $season)));
    }

    public function event(DirtPointsSystem $system, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver.nation', 'positions.driver.nation']);
        return view('dirt-rally.nationstandings.event')
            ->with('system', $system)
            ->with('event', $event)
            ->with('points', \DirtRallyPositions::addEquals(\DirtRallyNationPoints::forEvent($system, $event)));
    }
}
