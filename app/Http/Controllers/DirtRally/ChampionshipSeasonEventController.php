<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirtRally\ChampionshipSeasonEventRequest;
use App\Models\DirtRally\DirtEvent;

class ChampionshipSeasonEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
        $this->middleware('dirt-rally.validateSeason', ['only' => ['create', 'store']]);
        $this->middleware('dirt-rally.validateEvent', ['only' => ['show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function create($championship, $season)
    {
        return view('dirt-rally.event.create')
            ->with('season', \Request::get('season'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ChampionshipSeasonEventRequest $request
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipSeasonEventRequest $request, $championship, $season)
    {
        $season = \Request::get('season');
        $event = DirtEvent::create($request->all());
        $season->events()->save($event);
        \Notification::add('success', 'Event "'.$event->name.'" added to "'.$season->name.'"');
        return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function show($championship, $season, $event)
    {
        $event = \Request::get('event');
        return view('dirt-rally.event.show')
            ->with('event', $event)
            ->with('results', \DirtRallyResults::getEventResults($event));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function edit($championship, $season, $event)
    {
        return view('dirt-rally.event.edit')
            ->with('event', \Request::get('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipSeasonEventRequest $request
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipSeasonEventRequest $request, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->fill($request->all());
        $event->save();
        \Notification::add('success', $event->name . ' updated');
        return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($championship, $season, $event)
    {
        $event = \Request::get('event');
        if ($event->stages->count()) {
            \Notification::add('error',
                $event->name . ' cannot be deleted - there are stages assigned to  it');
            return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
        } else {
            $event->delete();
            \Notification::add('success', $event->name . ' deleted');
            return \Redirect::route('dirt-rally.championship.season.show', [$championship, $season]);
        }
    }
}
