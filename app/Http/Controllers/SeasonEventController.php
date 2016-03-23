<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonEventRequest;
use App\Models\Event;
use App\Models\Season;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SeasonEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  integer $seasonID
     * @return \Illuminate\Http\Response
     */
    public function create($seasonID)
    {
        return view('event.create')
            ->with('season', Season::findOrFail($seasonID));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SeasonEventRequest $request
     * @param  integer $seasonID
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonEventRequest $request, $seasonID)
    {
        $season = Season::findOrFail($seasonID);
        $event = Event::create($request->all());
        $season->events()->save($event);
        \Notification::add('success', 'Event "'.$event->name.'" added to "'.$season->name.'"');
        return \Redirect::route('season.event.show', [$seasonID, $event->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $seasonID
     * @param  int $eventID
     * @return \Illuminate\Http\Response
     */
    public function show($seasonID, $eventID)
    {
        return view('event.show')
            ->with('event', $this->getEvent($eventID, $seasonID))
            ->with('results', \Results::getEventResults($eventID));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $seasonID
     * @param  int $eventID
     * @return \Illuminate\Http\Response
     */
    public function edit($seasonID, $eventID)
    {
        return view('event.edit')
            ->with('event', $this->getEvent($eventID, $seasonID));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SeasonEventRequest $request
     * @param int $seasonID
     * @param int $eventID
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonEventRequest $request, $seasonID, $eventID)
    {
        $event = $this->getEvent($eventID, $seasonID);
        $event->fill($request->all());
        $event->save();
        \Notification::add('success', $event->name . ' updated');
        return \Redirect::route('season.event.show', [$seasonID, $eventID]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $seasonID
     * @param $eventID
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @internal param int $id
     */
    public function destroy($seasonID, $eventID)
    {
        $event = $this->getEvent($eventID, $seasonID);
        if ($event->stages->count()) {
            \Notification::add('error',
                $event->name . ' cannot be deleted - there are stages assigned to  it');
            return \Redirect::route('season.event.show', [$seasonID, $eventID]);
        } else {
            $event->delete();
            \Notification::add('success', $event->name . ' deleted');
            return \Redirect::route('season.show', [$event->season->id]);
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
}
