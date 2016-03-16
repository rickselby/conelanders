<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonEventStageRequest;
use App\Models\Event;
use App\Models\Stage;

class SeasonEventStageController extends Controller
{
    /** @var Event */
    protected $event;

    /** @var Stage */
    protected $stage;

    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $season_id
     * @param int $event_id
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
     * @param SeasonEventStageRequest $request
     * @param int $season_id
     * @param int $event_id
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonEventStageRequest $request, $season_id, $event_id)
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
     * @param int $season_id
     * @param int $event_id
     * @param int $stage_id
     * @return \Illuminate\Http\Response
     */
    public function show($season_id, $event_id, $stage_id)
    {
        if ($this->verifyStage($stage_id, $event_id, $season_id)) {
            return view('stage.show')
                ->with('stage', $this->stage)
                ->with('results', \Results::getStageResults($stage_id));
        } else {
            return $this->stageError();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $season_id
     * @param int $event_id
     * @param int $stage_id
     * @return \Illuminate\Http\Response
     */
    public function edit($season_id, $event_id, $stage_id)
    {
        if ($this->verifyStage($stage_id, $event_id, $season_id)) {
            return view('stage.edit')
                ->with('stage', $this->stage);
        } else {
            return $this->stageError();
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param SeasonEventStageRequest $request
     * @param int $season_id
     * @param int $event_id
     * @param int $stage_id
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonEventStageRequest $request, $season_id, $event_id, $stage_id)
    {
        if ($this->verifyStage($stage_id, $event_id, $season_id)) {
            $this->stage->fill($request->all());
            $this->stage->save();

            \Notification::add('success', $this->stage->name . ' updated');
            return \Redirect::route('season.event.stage.show', [
                'season_id' => $this->stage->event->season->id,
                'event_id' => $this->stage->event->id,
                'stage_id' => $this->stage->id,
            ]);
        } else {
            return $this->stageError();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $stage_id
     * @param int $event_id
     * @param int $season_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($stage_id, $event_id, $season_id)
    {
        if ($this->verifyStage($stage_id, $event_id, $season_id)) {
            if ($this->stage->results->count()) {
                \Notification::add('error', $this->stage->name . ' cannot be deleted - there are results for this stage');
                return \Redirect::route('season.event.stage.show', [
                    'season_id' => $this->stage->event->season->id,
                    'event_id' => $this->stage->event->id,
                    'stage_id' => $this->stage->id,
                ]);
            } else {
                $this->stage->delete();
                \Notification::add('success', $this->stage->name . ' deleted');
                return \Redirect::route('season.event.show', [
                    'season_id' => $this->stage->event->season->id,
                    'event_id' => $this->stage->event->id,
                ]);
            }
        } else {
            return $this->stageError();
        }
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
        \Notification::add('error', 'Could not find the requested event');
        return \Redirect::route('season.index');
    }

    /**
     * Verify the season_id, event_id and stage_id are valid, and match
     * @param int $stage_id
     * @param int $event_id
     * @param int $season_id
     * @return bool
     */
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

    /**
     * Return the generic stage error
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function stageError()
    {
        \Notification::add('error', 'Could not find the requested stage');
        return \Redirect::route('season.index');
    }
}
