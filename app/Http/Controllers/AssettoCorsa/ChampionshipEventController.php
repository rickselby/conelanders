<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssettoCorsa\ChampionshipEventRequest;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcEventEntrant;
use App\Services\AssettoCorsa\Import;
use App\Services\AssettoCorsa\Results;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChampionshipEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:assetto-corsa-admin');
        $this->middleware('assetto-corsa.validateEvent', ['except' => ['create', 'store']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param AcChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function create(AcChampionship $championship)
    {
        return view('assetto-corsa.event.create')
            ->with('championship', $championship);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipEventRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipEventRequest $request, AcChampionship $championship)
    {
        /** @var AcEvent $event */
        $event = $championship->events()->create($request->all());
        \Notification::add('success', 'Event "'.$event->name.'" created');
        return \Redirect::route('assetto-corsa.championship.event.show', [$championship, $event]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championshipSlug
     * @param  string $eventSlug
     * @return \Illuminate\Http\Response
     */
    public function show($championshipSlug, $eventSlug, Results $resultsService)
    {
        $event = \Request::get('event');
        return view('assetto-corsa.event.show')
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
        return view('assetto-corsa.event.edit')
            ->with('event', \Request::get('event'));
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
        $event->fill($request->all());
        $event->save();
        \Notification::add('success', 'Event "'.$event->name.'" updated');
        return \Redirect::route('assetto-corsa.championship.event.show', [$event->championship, $event]);
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
        if ($event->sessions()->count()) {
            \Notification::add('error', 'Event "'.$event->name.'" cannot be deleted - there are sessions added');
            return \Redirect::route('assetto-corsa.championship.event.show', [$event->championship, $event]);
        } else {
            $event->delete();
            \Notification::add('success', 'Event "'.$event->name.'" deleted');
            return \Redirect::route('assetto-corsa.championship.show', $event->championship);
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
        $fromEvent = AcEvent::findOrFail($request->get('from-event'));
        foreach($fromEvent->sessions AS $session) {
            $event->sessions()->create($session->toArray());
        }
        return \Redirect::route('assetto-corsa.championship.event.show', [$event->championship, $event]);
    }
}
