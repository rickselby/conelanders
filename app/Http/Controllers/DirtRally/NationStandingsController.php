<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\DirtChampionship;
use App\Http\Requests;
use App\Models\Nation;

class NationStandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('dirt-rally.validateSeason')->only(['season']);
        $this->middleware('dirt-rally.validateEvent')->only(['event', 'detail']);
    }

    public function championship(DirtChampionship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('dirt-rally.nationstandings.championship')
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \Positions::addEquals(\DirtRallyNationPoints::overall($seasons)));
    }

    public function overview(DirtChampionship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('dirt-rally.nationstandings.overview')
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \Positions::addEquals(\DirtRallyNationPoints::overview($seasons)));
    }

    public function season($championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver.nation', 'events.positions.driver.nation', 'championship']);
        return view('dirt-rally.nationstandings.season')
            ->with('season', $season)
            ->with('points', \Positions::addEquals(\DirtRallyNationPoints::forSeason($season)));
    }

    public function event($championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver.nation', 'positions.driver.nation']);
        return view('dirt-rally.nationstandings.event')
            ->with('event', $event)
            ->with('points', \Positions::addEquals(\DirtRallyNationPoints::forEvent($event)));
    }

    public function detail($championship, $season, $event, Nation $nation)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver.nation', 'positions.driver.nation']);
        return view('dirt-rally.nationstandings.detail')
            ->with('event', $event)
            ->with('nation', $nation)
            ->with('results', \DirtRallyNationPoints::details($event, $nation));
    }

}
