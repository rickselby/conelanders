<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonEventRequest;
use App\Models\Event;
use App\Models\Season;

class SeasonEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
        $this->middleware('validateEvent', ['only' => ['show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function create(Season $season)
    {
        return view('event.create')
            ->with('season', $season);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SeasonEventRequest $request
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonEventRequest $request, Season $season)
    {
        $event = Event::create($request->all());
        $season->events()->save($event);
        \Notification::add('success', 'Event "'.$event->name.'" added to "'.$season->name.'"');
        return \Redirect::route('season.event.show', [$season->id, $event->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $season
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function show($season, Event $event)
    {
        return view('event.show')
            ->with('event', $event)
            ->with('results', \Results::getEventResults($event));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $season
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function edit($season, Event $event)
    {
        return view('event.edit')
            ->with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SeasonEventRequest $request
     * @param int $season
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonEventRequest $request, $season, Event $event)
    {
        $event->fill($request->all());
        $event->save();
        \Notification::add('success', $event->name . ' updated');
        return \Redirect::route('season.event.show', [$season, $event->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $season
     * @param Event $event
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($season, Event $event)
    {
        if ($event->stages->count()) {
            \Notification::add('error',
                $event->name . ' cannot be deleted - there are stages assigned to  it');
            return \Redirect::route('season.event.show', [$season, $event->id]);
        } else {
            $event->delete();
            \Notification::add('success', $event->name . ' deleted');
            return \Redirect::route('season.show', [$event->season->id]);
        }
    }
}
