<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PointsSystem;
use App\Models\Season;
use App\Models\Stage;

use App\Http\Requests;

class StandingsController extends Controller
{

    public function show($system)
    {
        $pointsSystem = PointsSystem::findOrFail($system);
        $seasons = Season::with('events.stages.results.driver')->get()->sortBy('endDate');
        return view('standings.show')
            ->with('system', $pointsSystem)
            ->with('seasons', $seasons)
            ->with('points', \Points::overall($pointsSystem, $seasons));
    }

    public function season($system, $season)
    {
        $pointsSystem = PointsSystem::findOrFail($system);
        $season = Season::with('events.stages.results.driver')->findOrFail($season);
        return view('standings.season')
            ->with('system', $pointsSystem)
            ->with('season', $season)
            ->with('points', \Points::forSeason($pointsSystem, $season));
    }

    public function event($systemID, $seasonID, $eventID)
    {
        $event = Event::with(['season', 'stages.results.driver'])->findOrFail($eventID);
        if ($event->season->id != $seasonID) {
            throw new NotFoundHttpException();
        }

        $pointsSystem = PointsSystem::findOrFail($systemID);
        return view('standings.event')
            ->with('system', $pointsSystem)
            ->with('event', $event)
            ->with('points', \Points::forEvent($pointsSystem, $event));
    }

    public function stage($systemID, $seasonID, $eventID, $stageID)
    {
        $stage = Stage::with(['event.season'])->findOrFail($stageID);
        if ($stage->event->id != $eventID || $stage->event->season->id != $seasonID) {
            throw new NotFoundHttpException();
        }

        $pointsSystem = PointsSystem::findOrFail($systemID);
        return view('standings.stage')
            ->with('system', $pointsSystem)
            ->with('stage', $stage)
            ->with('results', \Results::getStageResults($stageID))
            ->with('points', \Points::forSystem($pointsSystem));
    }

}
