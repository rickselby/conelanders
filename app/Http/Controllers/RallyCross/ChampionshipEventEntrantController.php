<?php

namespace App\Http\Controllers\RallyCross;

use App\Events\RallyCross\EventEntrantsUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\RallyCross\ChampionshipEventEntrantRequest;
use App\Models\Driver;
use App\Models\RallyCross\RxCar;
use App\Models\RallyCross\RxEventEntrant;

class ChampionshipEventEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('rallycross.validateEvent', 
            ['only' => ['index', 'create', 'store']]
        );
    }

    public function index($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        $this->authorize('entrant', $event);
        return view('rallycross.event.entrant.index')
            ->with('event', $event);
    }

    public function create($championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        $this->authorize('create-entrant', $event);
        return view('rallycross.event.entrant.create')
            ->with('event', $event);
    }

    public function store(ChampionshipEventEntrantRequest $request, $championshipStub, $eventStub)
    {
        $event = \Request::get('event');
        $this->authorize('create-session', $event);
        $entrant = new RxEventEntrant();
        $entrant->car()->associate(RxCar::find($request->input('car')));
        $entrant->driver()->associate(Driver::where('name', $request->input('driver'))->first());
        $event->entrants()->save($entrant);

        \Event::fire(new EventEntrantsUpdated($event));
        \Notification::add('success', 'Entrant added');
        return \Redirect::route('rallycross.championship.event.entrant.index', [$event->championship, $event]);
    }

    public function edit($championshipStub, $eventStub, RxEventEntrant $entrant)
    {
        $this->authorize('edit-entrant', $entrant->event);

        return view('rallycross.event.entrant.edit')
            ->with('event', $entrant->event)
            ->with('entrant', $entrant);
    }

    public function update(ChampionshipEventEntrantRequest $request, $championshipStub, $eventStub, RxEventEntrant $entrant)
    {
        $this->authorize('edit-entrant', $entrant->event);

        $entrant->fill($request->except('driver_id'))->save();
        \Event::fire(new EventEntrantsUpdated($entrant->event));
        \Notification::add('success', 'Entrant updated');
        return \Redirect::route('rallycross.event.entrant.index', [$entrant->event->championship, $entrant->event]);
    }

    public function destroy($championshipStub, $eventStub, RxEventEntrant $entrant)
    {
        $this->authorize('delete-entrant', $entrant->event);

        if ($entrant->entries->count()) {
            \Notification::add('error', 'Entrant cannot be deleted - they have entries in sessions');
        } else {
            $entrant->delete();
            \Notification::add('success', 'Entrant deleted');
        }
        return \Redirect::route('rallycross.championship.event.entrant.index', [$entrant->event->championship, $entrant->event]);
    }
}
