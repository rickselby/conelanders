<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonEventRequest;
use App\Models\Event;
use App\Models\Season;

class ChampionshipSeasonEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
        $this->middleware('validateSeason', ['only' => ['create', 'store']]);
        $this->middleware('validateEvent', ['only' => ['show', 'edit', 'update', 'destroy']]);
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
        return view('event.create')
            ->with('season', \Request::get('season'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SeasonEventRequest $request
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonEventRequest $request, $championship, $season)
    {
        $season = \Request::get('season');
        $event = Event::create($request->all());
        $season->events()->save($event);
        \Notification::add('success', 'Event "'.$event->name.'" added to "'.$season->name.'"');
        return \Redirect::route('championship.season.event.show', [$championship, $season->id, $event->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $championship
     * @param  int $season
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function show($championship, $season, Event $event)
    {
        return view('event.show')
            ->with('event', $event)
            ->with('results', \Results::getEventResults($event));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $championship
     * @param  int $season
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function edit($championship, $season, Event $event)
    {
        return view('event.edit')
            ->with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SeasonEventRequest $request
     * @param  int $championship
     * @param  int $season
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonEventRequest $request, $championship, $season, Event $event)
    {
        $event->fill($request->all());
        $event->save();
        \Notification::add('success', $event->name . ' updated');
        return \Redirect::route('championship.season.event.show', [$championship, $season, $event->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $championship
     * @param  int $season
     * @param  Event $event
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($championship, $season, Event $event)
    {
        if ($event->stages->count()) {
            \Notification::add('error',
                $event->name . ' cannot be deleted - there are stages assigned to  it');
            return \Redirect::route('championship.season.event.show', [$championship, $season, $event->id]);
        } else {
            $event->delete();
            \Notification::add('success', $event->name . ' deleted');
            return \Redirect::route('championship.season.show', [$championship, $season]);
        }
    }
}
