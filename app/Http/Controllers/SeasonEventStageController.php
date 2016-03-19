<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonEventStageRequest;
use App\Models\Event;
use App\Models\Stage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeasonEventStageController extends Controller
{
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
        return view('stage.create')
            ->with('event', $this->getEvent($event_id, $season_id));
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
        $event = $this->getEvent($event_id, $season_id);
        $stage = Stage::create($request->all());
        $event->stages()->save($stage);
        \Notification::add('success', 'Stage "'.$stage->name.'" added to "'.$event->name.'" ('.$event->season->name.')');
        return \Redirect::route('season.event.show', ['season_id' => $event->season->id, 'event_id' => $event->id]);
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
        return view('stage.show')
            ->with('stage', $this->getStage($stage_id, $event_id, $season_id))
            ->with('results', \Results::getStageResults($stage_id));
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
        return view('stage.edit')
            ->with('stage', $this->getStage($stage_id, $event_id, $season_id));
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
        $stage = $this->getStage($stage_id, $event_id, $season_id);
        $stage->fill($request->all());
        $stage->save();

        \Notification::add('success', $stage->name . ' updated');
        return \Redirect::route('season.event.stage.show', [
            'season_id' => $stage->event->season->id,
            'event_id' => $stage->event->id,
            'stage_id' => $stage->id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $stage_id
     * @param int $event_id
     * @param int $season_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($season_id, $event_id, $stage_id)
    {
        $stage = $this->getStage($stage_id, $event_id, $season_id);
        if ($stage->results->count()) {
            \Notification::add('error', $stage->name . ' cannot be deleted - there are results for this stage');
            return \Redirect::route('season.event.stage.show', [
                'season_id' => $stage->event->season->id,
                'event_id' => $stage->event->id,
                'stage_id' => $stage->id,
            ]);
        } else {
            $stage->delete();
            \Notification::add('success', $stage->name . ' deleted');
            return \Redirect::route('season.event.show', [
                'season_id' => $stage->event->season->id,
                'event_id' => $stage->event->id,
            ]);
        }
    }

    /**
     * Verify the season_id and event_id are valid, and match
     * @param int $event_id
     * @param int $season_id
     * @return Event
     * @throws NotFoundHttpException
     */
    protected function getEvent($event_id, $season_id)
    {
        $event = Event::findOrFail($event_id);
        // Ensure the season matches too
        if ($event->season->id != $season_id) {
            throw new NotFoundHttpException();
        }
        return $event;
    }

    /**
     * Verify the season_id and event_id are valid, and match
     * @param int $stage_id
     * @param int $event_id
     * @param int $season_id
     * @return Event
     * @throws NotFoundHttpException
     */
    protected function getStage($stage_id, $event_id, $season_id)
    {
        $stage = Stage::findOrFail($stage_id);
        // Ensure the season matches too
        if ($stage->event->id != $event_id || $stage->event->season->id != $season_id) {
            throw new NotFoundHttpException();
        }
        return $stage;
    }

}
