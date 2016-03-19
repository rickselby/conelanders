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
     * @param  integer $season_id
     * @return \Illuminate\Http\Response
     */
    public function create($season_id)
    {
        return view('event.create')
            ->with('season', Season::findOrFail($season_id));
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
        $season = Season::findOrFail($season_id);
        $event = Event::create($request->all());
        $season->events()->save($event);
        \Notification::add('success', 'Event "'.$event->name.'" added to "'.$season->name.'"');
        return \Redirect::route('season.event.show', ['season' => $season_id, 'event' => $event->id]);
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
        return view('event.show')
            ->with('event', $this->getEvent($event_id, $season_id))
            ->with('results', \Results::getEventResults($event_id));
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
        return view('event.edit')
            ->with('event', $this->getEvent($event_id, $season_id));
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
        $event = $this->getEvent($event_id, $season_id);
        $event->fill($request->all());
        $event->save();
        \Notification::add('success', $event->name . ' updated');
        return \Redirect::route('season.event.show', ['season_id' => $season_id, 'event_id' => $event_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $season_id
     * @param $event_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @internal param int $id
     */
    public function destroy($season_id, $event_id)
    {
        $event = $this->getEvent($event_id, $season_id);
        if ($event->stages->count()) {
            \Notification::add('error',
                $event->name . ' cannot be deleted - there are stages assigned to  it');
            return \Redirect::route('season.event.show', ['season_id' => $season_id, 'event_id' => $event_id]);
        } else {
            $event->delete();
            \Notification::add('success', $event->name . ' deleted');
            return \Redirect::route('season.show', ['season_id' => $event->season->id]);
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
}
