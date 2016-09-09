<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Events\AssettoCorsa\CarUpdated;
use App\Events\AssettoCorsa\ChampionshipUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssettoCorsa\CarRequest;
use App\Http\Requests\AssettoCorsa\ChampionshipRequest;
use App\Models\AssettoCorsa\AcCar;
use App\Models\AssettoCorsa\AcChampionship;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:assetto-corsa-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('assetto-corsa.car.index')
            ->with('cars', AcCar::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('assetto-corsa.car.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CarRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarRequest $request)
    {
        /** @var AcChampionship $championship */
        $car = AcCar::create($request->only('ac_identifier', 'name', 'full_name'));
        \Notification::add('success', 'Car "'.$car->name.'" added');
        return \Redirect::route('assetto-corsa.car.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  AcChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function edit(AcCar $car)
    {
        return view('assetto-corsa.car.edit')
            ->with('car', $car);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CarRequest $request
     * @param  AcCar $championship
     * @return \Illuminate\Http\Response
     */
    public function update(CarRequest $request, AcCar $car)
    {
        $car->fill($request->only('ac_identifier', 'name', 'full_name'))->save();
        \Event::fire(new CarUpdated($car));
        \Notification::add('success', 'Car "'.$car->name.'" updated');
        return \Redirect::route('assetto-corsa.car.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AcCar $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcCar $car)
    {
        if ($car->entrants->count()) {
            \Notification::add('error', 'Car "'.$car->name.'" cannot be deleted - there are entrants that use it');
        } else {
            $car->delete();
            \Notification::add('success', 'Car "'.$car->name.'" deleted');
        }
        return \Redirect::route('assetto-corsa.car.index');
    }
}
