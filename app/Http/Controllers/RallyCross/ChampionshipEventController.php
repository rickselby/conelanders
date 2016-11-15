<?php

namespace App\Http\Controllers\RallyCross;

use App\Events\RallyCross\EventUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\RallyCross\ChampionshipEventRequest;
use App\Http\Requests\RallyCross\ChampionshipRequest;
use App\Models\PointsSequence;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;
use App\Services\RallyCross\Event;
use Illuminate\Http\Request;
use App\Models\RallyCross\RxChampionship;
use Carbon\Carbon;

class ChampionshipEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('rallycross.validateEvent', ['except' => ['create', 'store']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(RxChampionship $championship)
    {
        $this->authorize('create-event', $championship);
        return view('rallycross.event.create')
            ->with('championship', $championship);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipEventRequest $request, RxChampionship $championship)
    {
        $this->authorize('create-event', $championship);
        /** @var RxChampionship $championship */
        $event = $championship->events()->create($request->only('name', 'time'));
        \Notification::add('success', 'Event "'.$event->name.'" added to "'.$championship->name.'"');
        return \Redirect::route('rallycross.championship.event.show', [$championship, $event]);
    }

    /**
     * Display the specified resource.
     *
     * @param  RxChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function show($championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('view', $event);
        return view('rallycross.event.show')
            ->with('event', $event)
            ->with('sequences', \PointSequences::forSelect())
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
        return view('rallycross.event.edit')
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

        $event->fill($request->only('name', 'time'))->save();

        \Event::fire(new EventUpdated($event));
        \Notification::add('success', 'Event "'.$event->name.'" updated');
        return \Redirect::route('rallycross.championship.event.show', [$event->championship, $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RxChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function destroy($championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('delete', $event);

        if ($event->sessions->count()) {
            \Notification::add('error', 'Event "'.$event->name.'" cannot be deleted - there are sessions assigned to it');
            return \Redirect::route('rallycross.championship.event.show', [$event->championship, $event]);
        } else {
            $event->delete();
            \Event::fire(new EventUpdated($event));
            \Notification::add('success', 'Event "'.$event->name.'" deleted');
            return \Redirect::route('rallycross.championship.show', $event->championship);
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

        $fromEvent = RxEvent::findOrFail($request->get('from-event'));
        foreach($fromEvent->sessions AS $session) {
            $event->sessions()->create($session->toArray());
        }
        return \Redirect::route('rallycross.championship.event.show', [$event->championship, $event]);
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
            $session = $event->sessions()->findOrFail($sessionID);
            $session->order = $counter++;
            $session->save();
        }
    }

    /**
     * Update the release date for the event
     *
     * @param Request $request
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param string $sessionSlug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function releaseDate(Request $request, $championshipSlug, $eventSlug)
    {
        $event = \Request::get('event');
        $this->authorize('update', $event);

        $event->release = Carbon::createFromFormat('jS F Y, H:i', $request->release);
        $event->save();
        \Event::fire(new EventUpdated($event));
        \Notification::add('success', 'Release Date Updated');
        return \Redirect::route('rallycross.championship.event.show', [$event->championship, $event]);
    }

    /**
     * Update Heats Points based on a sequence
     *
     * @param Request $request
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param Event $eventService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function heatsPointsSequence(Request $request, $championshipSlug, $eventSlug, Event $eventService)
    {
        $event = \Request::get('event');
        $this->authorize('update', $event);

        $sequence = PointsSequence::findOrFail($request->get('sequence'));
        $eventService->applyHeatsPointsSequence($event, $sequence);
        \Event::fire(new EventUpdated($event));
        \Notification::add('success', 'Points sequence applied to heats results');
        return \Redirect::route('rallycross.championship.event.show', [$event->championship, $event]);
    }

    /**
     * Update Heats Points from given points
     *
     * @param Request $request
     * @param string $championshipSlug
     * @param string $eventSlug
     * @param Event $eventService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function heatsPoints(Request $request, $championshipSlug, $eventSlug, Event $eventService)
    {
        $event = \Request::get('event');
        $this->authorize('update', $event);

        $eventService->setHeatsPoints($event, $request->get('points'));
        \Event::fire(new EventUpdated($event));
        \Notification::add('success', 'Points sequence applied to heats results');
        return \Redirect::route('rallycross.championship.event.show', [$event->championship, $event]);
    }
}
