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
     * @param int $seasonID
     * @param int $eventID
     * @return \Illuminate\Http\Response
     */
    public function create($seasonID, $eventID)
    {
        return view('stage.create')
            ->with('event', $this->getEvent($eventID, $seasonID));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SeasonEventStageRequest $request
     * @param int $seasonID
     * @param int $eventID
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonEventStageRequest $request, $seasonID, $eventID)
    {
        $event = $this->getEvent($eventID, $seasonID);
        $stage = Stage::create($request->all());
        $event->stages()->save($stage);
        \Notification::add('success', 'Stage "'.$stage->name.'" added to "'.$event->name.'" ('.$event->season->name.')');
        return \Redirect::route('season.event.show', [$event->season->id, $event->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $seasonID
     * @param int $eventID
     * @param int $stageID
     * @return \Illuminate\Http\Response
     */
    public function show($seasonID, $eventID, $stageID)
    {
        return view('stage.show')
            ->with('stage', $this->getStage($stageID, $eventID, $seasonID))
            ->with('results', \Results::getStageResults($stageID));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $seasonID
     * @param int $eventID
     * @param int $stageID
     * @return \Illuminate\Http\Response
     */
    public function edit($seasonID, $eventID, $stageID)
    {
        return view('stage.edit')
            ->with('stage', $this->getStage($stageID, $eventID, $seasonID));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param SeasonEventStageRequest $request
     * @param int $seasonID
     * @param int $eventID
     * @param int $stageID
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonEventStageRequest $request, $seasonID, $eventID, $stageID)
    {
        $stage = $this->getStage($stageID, $eventID, $seasonID);
        $stage->fill($request->all());
        $stage->save();

        \Notification::add('success', $stage->name . ' updated');
        return \Redirect::route('season.event.stage.show', [$stage->event->season->id, $stage->event->id, $stage->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $stageID
     * @param int $eventID
     * @param int $seasonID
     * @return \Illuminate\Http\Response
     */
    public function destroy($seasonID, $eventID, $stageID)
    {
        $stage = $this->getStage($stageID, $eventID, $seasonID);
        if ($stage->results->count()) {
            \Notification::add('error', $stage->name . ' cannot be deleted - there are results for this stage');
            return \Redirect::route('season.event.stage.show', [$stage->event->season->id, $stage->event->id, $stage->id]);
        } else {
            $stage->delete();
            \Notification::add('success', $stage->name . ' deleted');
            return \Redirect::route('season.event.show', [$stage->event->season->id, $stage->event->id]);
        }
    }

    /**
     * Verify the season_id and event_id are valid, and match
     * @param int $eventID
     * @param int $seasonID
     * @return Event
     * @throws NotFoundHttpException
     */
    protected function getEvent($eventID, $seasonID)
    {
        $event = Event::findOrFail($eventID);
        // Ensure the season matches too
        if ($event->season->id != $seasonID) {
            throw new NotFoundHttpException();
        }
        return $event;
    }

    /**
     * Verify the season_id and event_id are valid, and match
     * @param int $stageID
     * @param int $eventID
     * @param int $seasonID
     * @return Event
     * @throws NotFoundHttpException
     */
    protected function getStage($stageID, $eventID, $seasonID)
    {
        $stage = Stage::findOrFail($stageID);
        // Ensure the season matches too
        if ($stage->event->id != $eventID || $stage->event->season->id != $seasonID) {
            throw new NotFoundHttpException();
        }
        return $stage;
    }

}
