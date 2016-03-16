<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonEventRequest;
use App\Models\Event;
use App\Models\Season;


class SeasonEventController extends Controller
{
    /** @var Season */
    protected $season;

    /** @var Event */
    protected $event;

    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  integer $season_id
     * @return \Illuminate\Http\Response
     */
    public function create($season_id)
    {
        if ($this->verifySeason($season_id)) {
            return view('event.create')
                ->with('season', $this->season);
        } else {
            return $this->seasonError();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SeasonEventRequest $request
     * @param  integer $season_id
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonEventRequest $request, $season_id)
    {
        if ($this->verifySeason($season_id)) {
            $event = Event::create($request->all());
            $this->season->events()->save($event);
            \Notification::add('success', 'Event "'.$event->name.'" added to "'.$this->season->name.'"');
            return \Redirect::route('season.event.show', ['season' => $season_id, 'event' => $event->id]);
        } else {
            return $this->seasonError();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $season_id
     * @param  int $event_id
     * @return \Illuminate\Http\Response
     */
    public function show($season_id, $event_id)
    {
        if ($this->verifyEvent($event_id, $season_id)) {
            return view('event.show')
                ->with('event', $this->event)
                ->with('results', \Results::getEventResults($event_id));
        } else {
            return $this->eventError();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $season_id
     * @param  int $event_id
     * @return \Illuminate\Http\Response
     */
    public function edit($season_id, $event_id)
    {
        if ($this->verifyEvent($event_id, $season_id)) {
            return view('event.edit')
                ->with('event', $this->event);
        } else {
            return $this->eventError();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SeasonEventRequest $request
     * @param int $season_id
     * @param int $event_id
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonEventRequest $request, $season_id, $event_id)
    {
        if ($this->verifyEvent($event_id, $season_id)) {
            $this->event->fill($request->all());
            $this->event->save();

            \Notification::add('success', $this->event->name . ' updated');
            return \Redirect::route('season.event.show', ['season_id' => $season_id, 'event_id' => $event_id]);
        } else {
            return $this->eventError();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $event_id
     * @param $season_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @internal param int $id
     */
    public function destroy($event_id, $season_id)
    {
        if ($this->verifyEvent($event_id, $season_id)) {
            if ($this->event->stages->count()) {
                \Notification::add('error',
                    $this->event->name . ' cannot be deleted - there are stages assigned to  it');
                return \Redirect::route('season.event.show', ['season_id' => $season_id, 'event_id' => $event_id]);
            } else {
                $this->event->delete();
                \Notification::add('success', $this->event->name . ' deleted');
                return \Redirect::route('season.show', ['season_id' => $this->stage->event->season->id]);
            }
        }
    }

    /**
     * Verify the season_id given is valid
     * @param int $season_id
     * @return bool
     */
    protected function verifySeason($season_id)
    {
        $this->season = Season::find($season_id);
        return $this->season->exists;
    }

    /**
     * Return the generic season error
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function seasonError()
    {
        \Notification::add('error', 'Could not find requested season');
        return \Redirect::route('season.index');
    }

    /**
     * Verify the season_id and event_id are valid, and match
     * @param int $event_id
     * @param int $season_id
     * @return bool
     */
    protected function verifyEvent($event_id, $season_id)
    {
        $this->event = Event::find($event_id);
        if ($this->event->exists) {
            return $this->event->season->id == $season_id;
        } else {
            return false;
        }
    }

    /**
     * Return the generic event error
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function eventError()
    {
        \Notification::add('error', 'Could not find requested event');
        return \Redirect::route('season.index');
    }
}
