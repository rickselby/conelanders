<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Stage;
use Illuminate\Http\Request;

use App\Http\Requests;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('stage.index')
            ->with('stages', Stage::get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $event = Event::with('season')->find($request->eventID);
        if ($event->exists) {
            return view('stage.create')
                ->with('event', $event);
        } else {
            \Notification::add('error', 'Could not find requested event');
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
        $event = Event::with('season')->find($request->eventID);
        if ($event->exists) {
            $stage = Stage::create($request->all());
            $event->stages()->save($stage);
            \Notification::add('success', $stage->name . ' created');
            return \Redirect::route('event.show', ['id' => $event->id]);
        } else {
            \Notification::add('error', 'Could not find requested event');
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
        return view('stage.show')
            ->with('stage', Stage::with(['event.season'])->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('stage.edit')
            ->with('stage', Stage::find($id));
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
        $stage = Stage::find($id);
        $stage->fill($request->all());
        $stage->save();

        \Notification::add('success', $stage->name.' updated');
        return \Redirect::route('stage.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stage = Stage::with('results')->find($id);
        if ($stage->results->count()) {
            \Notification::add('error', $stage->name.' cannot be deleted - there are results for this stage');
            return \Redirect::route('stage.show', ['id' => $id]);
        } else {
            $stage->delete();
            \Notification::add('success', $stage->name.' deleted');
            return \Redirect::route('stage.index');
        }
    }
}
