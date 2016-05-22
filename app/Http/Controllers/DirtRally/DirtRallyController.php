<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\DirtChampionship;
use App\Services\DirtRally\Championships;
use Carbon\Carbon;

class DirtRallyController extends Controller
{
    public function __construct()
    {
        $this->middleware('dirt-rally.validateEvent')->only(['event']);
    }

    public function index(Championships $championships)
    {
        return view('dirt-rally.index')
            ->with('currentChampionship', $championships->getCurrent())
            ->with('completeChampionships', $championships->getComplete())
            ->with('currentEvents', \DirtRallyEvents::getCurrent());
    }

    public function championship(DirtChampionship $championship)
    {
        return view('dirt-rally.championship')
            ->with('championship', $championship);
    }

    public function event($champSlug, $seasonSlug, $eventSlug)
    {
        $event = \Request::get('event');

        if ($event->opens->lt(Carbon::now()) && $event->closes->gt(Carbon::now())) {
            $event->load('stages.results.driver', 'positions.driver');
            return view('dirt-rally.event')
                ->with('event', $event)
                ->with('results', \Positions::addEquals(\DirtRallyResults::getEventResults($event)));
        } else {
            return \Redirect::route('dirt-rally.standings.event', [$event->season->championship, $event->season, $event]);
        }
    }
}