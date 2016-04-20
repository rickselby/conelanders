<?php

namespace App\Http\Controllers;

use App\Models\Championship;
use App\Models\Event;
use App\Models\Season;
use App\Models\Stage;

use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TimesController extends Controller
{
    public function __construct()
    {
        $this->middleware('validateSeason')->only(['season']);
        $this->middleware('validateEvent')->only(['event']);
        $this->middleware('validateStage')->only(['stage']);
    }

    public function index()
    {
        return view('times.index')
            ->with('championships', Championship::all()->sortBy('closes'));
    }

    public function championship(Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('closes');
        return view('times.championship')
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('times', \Times::overall($seasons));
    }

    public function season($championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver', 'events.positions.driver']);
        return view('times.season')
            ->with('season', $season)
            ->with('times', \Times::forSeason($season));
    }

    public function event($championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season', 'stages.results.driver', 'positions.driver']);
        return view('times.event')
            ->with('event', $event)
            ->with('times', \Times::forEvent($event));
    }

    public function stage($championship, $season, $event, $stage)
    {
        $stage = \Request::get('stage');
        $stage->load('event.season');
        return view('times.stage')
            ->with('stage', $stage)
            ->with('results', \Results::getStageResults($stage->id));
    }

}
