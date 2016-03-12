<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Season;
use Illuminate\Http\Request;

use App\Http\Requests;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('event.index')
            ->with('events', Event::with('stages')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $season = Season::find($request->seasonID);
        if ($season->exists) {
            return view('event.create')
                ->with('season', $season);
        } else {
            \Notification::add('error', 'Could not find requested season');
            return \Redirect::route('season.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $season = Season::find($request->seasonID);
        if ($season->exists) {
            $event = Event::create($request->all());
            $season->events()->save($event);
            \Notification::add('success', $event->name.' created');
            return \Redirect::route('event.show', ['id' => $event->id]);
        } else {
            \Notification::add('error', 'Could not find requested season');
            return \Redirect::route('season.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('event.show')
            ->with('event', Event::with(['season', 'stages'])->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('event.edit')
            ->with('event', Event::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        $event->fill($request->all());
        $event->save();

        \Notification::add('success', $event->name.' updated');
        return \Redirect::route('event.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
