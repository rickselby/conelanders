<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\CarUpdated;
use App\Events\Races\ChampionshipUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\CarRequest;
use App\Http\Requests\Races\ChampionshipRequest;
use App\Models\Races\RacesCar;
use App\Models\Races\RacesChampionship;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:races-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('races.car.index')
            ->with('cars', RacesCar::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('races.car.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CarRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarRequest $request)
    {
        /** @var RacesChampionship $championship */
        $car = RacesCar::create($request->only('ac_identifier', 'name', 'short_name'));
        \Notification::add('success', 'Car "'.$car->name.'" added');
        return \Redirect::route('races.car.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  RacesChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function edit(RacesCar $car)
    {
        return view('races.car.edit')
            ->with('car', $car);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CarRequest $request
     * @param  RacesCar $championship
     * @return \Illuminate\Http\Response
     */
    public function update(CarRequest $request, RacesCar $car)
    {
        $car->fill($request->only('ac_identifier', 'name', 'short_name'))->save();
        \Event::fire(new CarUpdated($car));
        \Notification::add('success', 'Car "'.$car->name.'" updated');
        return \Redirect::route('races.car.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RacesCar $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(RacesCar $car)
    {
        if ($car->entrants->count()) {
            \Notification::add('error', 'Car "'.$car->name.'" cannot be deleted - there are entrants that use it');
        } else {
            $car->delete();
            \Notification::add('success', 'Car "'.$car->name.'" deleted');
        }
        return \Redirect::route('races.car.index');
    }
}
