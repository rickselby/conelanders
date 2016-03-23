<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PointsSystem;
use App\Models\Season;
use App\Models\Stage;

use App\Http\Requests;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('validateEvent')->only(['event']);
        $this->middleware('validateStage')->only(['stage']);
    }

    public function show(PointsSystem $system)
    {
        $seasons = Season::with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('endDate');
        return view('standings.show')
            ->with('system', $system)
            ->with('seasons', $seasons)
            ->with('points', \Points::overall($system, $seasons));
    }

    public function season(PointsSystem $system, Season $season)
    {
        $season->load(['events.stages.results.driver', 'events.positions.driver']);
        return view('standings.season')
            ->with('system', $system)
            ->with('season', $season)
            ->with('points', \Points::forSeason($system, $season));
    }

    public function event(PointsSystem $system, $season, Event $event)
    {
        $event->load(['season', 'stages.results.driver', 'positions.driver']);
        return view('standings.event')
            ->with('system', $system)
            ->with('event', $event)
            ->with('points', \Points::forEvent($system, $event));
    }

    public function stage(PointsSystem $system, $season, $event, Stage $stage)
    {
        $stage->load(['event.season']);
        return view('standings.stage')
            ->with('system', $system)
            ->with('stage', $stage)
            ->with('results', \Results::getStageResults($stage->id))
            ->with('points', \Points::forSystem($system));
    }

}
