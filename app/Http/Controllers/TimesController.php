<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Season;
use App\Models\Stage;

use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TimesController extends Controller
{
    public function __construct()
    {
        $this->middleware('validateEvent')->only(['event']);
        $this->middleware('validateStage')->only(['stage']);
    }

    public function index()
    {
        $seasons = Season::with(['events.stages.results.driver', 'events.positions.driver'])->get()->sortBy('endDate');
        return view('times.index')
            ->with('seasons', $seasons)
            ->with('times', \Times::overall($seasons));
    }

    public function season(Season $season)
    {
        $season->load(['events.stages.results.driver', 'events.positions.driver']);
        return view('times.season')
            ->with('season', $season)
            ->with('times', \Times::forSeason($season));
    }

    public function event($season, Event $event)
    {
        $event->load(['season', 'stages.results.driver', 'positions.driver']);
        return view('times.event')
            ->with('event', $event)
            ->with('times', \Times::forEvent($event));
    }

    public function stage($season, $event, Stage $stage)
    {
        $stage->load('event.season');
        return view('times.stage')
            ->with('stage', $stage)
            ->with('results', \Results::getStageResults($stage->id));
    }

}
