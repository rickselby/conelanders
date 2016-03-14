<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Season;
use Illuminate\Http\Request;

use App\Http\Requests;

class SeasonEventController extends Controller
{
    /** @var Season */
    protected $season;

    /** @var Event */
    protected $event;

    public function __construct()
    {
        $this->middleware('admin', ['except' =>
            ['index', 'show']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
    public function index()
    {
        return view('event.index')
            ->with('events', Event::with('stages')->get());
    }
     */

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
     * @param  integer $season_id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($season_id, Request $request)
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
     *
    public function edit($season_id, $event_id)
    {
        if ($this->verifyEvent($event_id, $season_id)) {
            return view('event.edit')
                ->with('event', $this->event);
        } else {
            return $this->eventError();
        }
    }
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        $event->fill($request->all());
        $event->save();

        \Notification::add('success', $event->name.' updated');
        return \Redirect::route('event.show', ['id' => $id]);
    }
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
    public function destroy($id)
    {
        $event = Event::with('stages')->find($id);
        if ($event->stages->count()) {
            \Notification::add('error', $event->name.' cannot be deleted - there are stages assigned to  it');
            return \Redirect::route('event.show', ['id' => $id]);
        } else {
            $event->delete();
            \Notification::add('success', $event->name.' deleted');
            return \Redirect::route('event.index');
        }
    }
     */

    protected function verifySeason($season_id)
    {
        $this->season = Season::find($season_id);
        return $this->season->exists;
    }

    protected function seasonError()
    {
        \Notification::add('error', 'Could not find requested season');
        return \Redirect::route('season.index');
    }

    protected function verifyEvent($event_id, $season_id)
    {
        $this->event = Event::find($event_id);
        if ($this->event->exists) {
            return $this->event->season->id == $season_id;
        } else {
            return false;
        }
    }

    protected function eventError()
    {
        \Notification::add('error', 'Could not find requested event');
        return \Redirect::route('season.index');
    }
}
