<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Stage;
use Illuminate\Http\Request;

use App\Http\Requests;

class SeasonEventStageController extends Controller
{
    /** @var Event */
    protected $event;

    /** @var Stage */
    protected $stage;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
    public function index()
    {
        return view('stage.index')
            ->with('stages', Stage::get());
    }
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($season_id, $event_id)
    {
        if ($this->verifyEvent($event_id, $season_id)) {
            return view('stage.create')
                ->with('event', $this->event);
        } else {
            return $this->eventError();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($season_id, $event_id, Request $request)
    {
        if ($this->verifyEvent($event_id, $season_id)) {
            $stage = Stage::create($request->all());
            $this->event->stages()->save($stage);
            \Notification::add('success', 'Stage "'.$stage->name.'" added to "'.$this->event->name.'" ('.$this->event->season->name.')');
            return \Redirect::route('season.event.show', ['season_id' => $this->event->season->id, 'event_id' => $this->event->id]);
        } else {
            return $this->eventError();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($season_id, $event_id, $stage_id)
    {
        if ($this->verifyStage($stage_id, $event_id, $season_id)) {
            return view('stage.show')
                ->with('stage', $this->stage);
        } else {
            return $this->stageError();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
    public function edit($id)
    {
        return view('stage.edit')
            ->with('stage', Stage::find($id));
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
        $stage = Stage::find($id);
        $stage->fill($request->all());
        $stage->save();

        \Notification::add('success', $stage->name.' updated');
        return \Redirect::route('stage.show', ['id' => $id]);
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

    protected function eventError()
    {
        \Notification::add('error', 'Could not find the requested event');
        return \Redirect::route('season.index');
    }

    protected function verifyStage($stage_id, $event_id, $season_id)
    {
        $this->stage = Stage::find($stage_id);
        if ($this->stage->exists) {
            return $this->stage->event->id == $event_id
                && $this->stage->event->season->id = $season_id;
        } else {
            return false;
        }
    }

    protected function stageError()
    {
        \Notification::add('error', 'Could not find the requested stage');
        return \Redirect::route('season.index');
    }
}
