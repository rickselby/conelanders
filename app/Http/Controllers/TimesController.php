<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Season;
use App\Models\Stage;

use App\Http\Requests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TimesController extends Controller
{

    public function index()
    {
        $seasons = Season::with('events.stages.results.driver')->get()->sortBy('endDate');
        return view('times.index')
            ->with('seasons', $seasons)
            ->with('times', \Times::overall($seasons));
    }

    public function season($season)
    {
        $season = Season::with('events.stages.results.driver')->findOrFail($season);
        return view('times.season')
            ->with('season', $season)
            ->with('times', \Times::forSeason($season));
    }

    public function event($seasonID, $eventID)
    {
        $event = Event::with(['season', 'stages.results.driver'])->findOrFail($eventID);
        if ($event->season->id != $seasonID) {
            throw new NotFoundHttpException();
        }

        return view('times.event')
            ->with('event', $event)
            ->with('times', \Times::forEvent($event));
    }

    public function stage($seasonID, $eventID, $stageID)
    {
        $stage = Stage::with(['event.season'])->findOrFail($stageID);
        if ($stage->event->id != $eventID || $stage->event->season->id != $seasonID) {
            throw new NotFoundHttpException();
        }

        return view('times.stage')
            ->with('stage', $stage)
            ->with('results', \Results::getStageResults($stageID));
    }

}
