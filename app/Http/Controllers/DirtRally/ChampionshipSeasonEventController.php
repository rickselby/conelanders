<?php

namespace App\Http\Controllers\DirtRally;

use App\Events\DirtRally\EventImport;
use App\Events\DirtRally\EventUpdated;
use App\Events\DirtRally\SeasonUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\DirtRally\ChampionshipSeasonEventRequest;
use App\Models\DirtRally\DirtCar;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtResult;
use App\Models\Driver;
use App\Services\DirtRally\Cars;
use Illuminate\Http\Request;

class ChampionshipSeasonEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:dirt-rally-admin');
        $this->middleware('dirt-rally.validateSeason', ['only' => ['create', 'store']]);
        $this->middleware('dirt-rally.validateEvent', ['only' => ['show', 'edit', 'update', 'destroy', 'cars', 'updateCars']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function create($championship, $season)
    {
        return view('dirt-rally.event.create')
            ->with('season', \Request::get('season'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ChampionshipSeasonEventRequest $request
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipSeasonEventRequest $request, $championship, $season)
    {
        $season = \Request::get('season');
        $event = $season->events()->create($request->all());
        if ($request->get('playlistLink')) {
            $event->playlist()->create(['link' => $request->get('playlistLink')]);
        }
        \Event::fire(new SeasonUpdated($season));
        \Notification::add('success', 'Event "'.$event->name.'" added to "'.$season->name.'"');
        if ($request->get('racenet_event_id')) {
            \Event::fire(new EventImport($event));
            \Notification::add('success', 'Stage import queued - pulling stage information from the dirt website in the background. Refresh the page to see if it\'s done yet...');
        }
        return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function show($championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load('stages.results.driver', 'positions.driver');
        return view('dirt-rally.event.show')
            ->with('event', $event)
            ->with('results', \Positions::addEquals(\DirtRallyResults::getEventResults($event)));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function edit($championship, $season, $event)
    {
        return view('dirt-rally.event.edit')
            ->with('event', \Request::get('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipSeasonEventRequest $request
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipSeasonEventRequest $request, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->fill($request->all());
        if ($event->playlist) {
            if ($request->get('playlistLink')) {
                $event->playlist->fill(['link' => $request->get('playlistLink')]);
                $event->playlist->save();
            } else {
                $event->playlist->delete();
            }
        } elseif ($request->get('playlistLink')) {
            $event->playlist()->create(['link' => $request->get('playlistLink')]);
        }
        $event->save();
        \Event::fire(new EventUpdated($event));
        # Has the event ID changed? Try to import if it has
        if ($request->get('racenet_event_id') != $event->getOriginal('racenet_event_id')) {
            \Event::fire(new EventImport($event));
        }


        \Notification::add('success', $event->name . ' updated');
        return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $championship
     * @param  string $season
     * @param  string $event
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($championship, $season, $event)
    {
        $event = \Request::get('event');
        if ($event->stages->count()) {
            \Notification::add('error',
                $event->name . ' cannot be deleted - there are stages assigned to  it');
            return \Redirect::route('dirt-rally.championship.season.event.show', [$championship, $season, $event]);
        } else {
            $event->delete();
            \Event::fire(new EventUpdated($event));
            \Notification::add('success', $event->name . ' deleted');
            return \Redirect::route('dirt-rally.championship.season.show', [$championship, $season]);
        }
    }

    public function cars($championship, $season, $event)
    {
        $event = \Request::get('event');
        return view('dirt-rally.event.cars')
            ->with('event', $event)
            ->with('cars', DirtCar::all());
    }

    public function updateCars(Request $request, $championship, $season, $event, Cars $cars)
    {
        $event = \Request::get('event');

        foreach($request->get('car') AS $driverID => $carID) {
            $car = DirtCar::find($carID);
            if ($car) {
                $cars->updateForEvent($event, Driver::findOrFail($driverID), $car);
            }
        }

        return \Redirect::route('dirt-rally.championship.season.event.cars', [$championship, $season, $event]);
    }
}
