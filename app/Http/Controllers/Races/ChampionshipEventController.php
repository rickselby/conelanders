<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\ChampionshipUpdated;
use App\Events\Races\EventUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\ChampionshipEventRequest;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesSession;
use App\Services\Races\Signups;
use Illuminate\Http\Request;

class ChampionshipEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'signup']);
        $this->middleware('races.validateEvent', ['except' => ['create', 'store']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param RacesChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function create(RacesChampionship $championship)
    {
        $this->authorize('create-event', $championship);
        return view('races.event.create')
            ->with('championship', $championship);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipEventRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipEventRequest $request, RacesChampionship $championship)
    {
        $this->authorize('create-event', $championship);
        /** @var RacesEvent $event */
        $event = $championship->events()->create($request->all());
        \Event::fire(new ChampionshipUpdated($championship));
        \Notification::add('success', 'Event "'.$event->name.'" created');
        return \Redirect::route('races.championship.event.show', [$championship, $event]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @return \Illuminate\Http\Response
     */
    public function show($championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('view', $event);
        return view('races.event.show')
            ->with('event', $event)
            ->with('otherEvents', $event->championship->events()->where('id', '!=', $event->id)->pluck('name', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @return \Illuminate\Http\Response
     */
    public function edit($championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('update', $event);
        return view('races.event.edit')
            ->with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipEventRequest $request
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipEventRequest $request, $championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('update', $event);
        $event->fill($request->all());
        $event->save();
        \Event::fire(new EventUpdated($event));
        \Notification::add('success', 'Event "'.$event->name.'" updated');
        return \Redirect::route('races.championship.event.show', [$event->championship, $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @return \Illuminate\Http\Response
     */
    public function destroy($championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('delete', $event);

        if ($event->sessions()->count()) {
            \Notification::add('error', 'Event "'.$event->name.'" cannot be deleted - there are sessions added');
            return \Redirect::route('races.championship.event.show', [$event->championship, $event]);
        } else {
            $event->delete();
            \Event::fire(new EventUpdated($event));
            \Notification::add('success', 'Event "'.$event->name.'" deleted');
            return \Redirect::route('races.championship.show', $event->championship);
        }
    }

    /**
     * Copy sessions from one event to another
     *
     * @param Request $request
     * @param $championshipSlug
     * @param $eventSlug
     */
    public function copySessions(Request $request, $championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('update', $event);

        $fromEvent = RacesEvent::findOrFail($request->get('from-event'));
        foreach($fromEvent->sessions AS $session) {
            $event->sessions()->create($session->toArray());
        }
        return \Redirect::route('races.championship.event.show', [$event->championship, $event]);
    }

    /**
     * Set the ordering of sessions for this event
     * @param Request $request
     * @param $championshipSlug
     * @param $eventSlug
     */
    public function sortSessions(Request $request, $championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('update', $event);

        $counter = 1;
        foreach($request->get('order') AS $sessionID) {
            $session = RacesSession::findOrFail($sessionID);
            $session->order = $counter++;
            $session->save();
        }
    }

    public function signup(Request $request, $championshipSlug, $eventSlug, Signups $signups)
    {
        $event = \Request::get('event');
        $signups->setStatus($event, $request->get('status'));
        \Notification::add('success', 'Attendance updated');
        return \Redirect::route('home');
    }
}
