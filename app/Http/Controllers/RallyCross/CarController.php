<?php

namespace App\Http\Controllers\RallyCross;

use App\Events\RallyCross\CarUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\RallyCross\CarRequest;
use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxCar;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:rallycross-admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('rallycross.car.index')
            ->with('cars', RxCar::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rallycross.car.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CarRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarRequest $request)
    {
        /** @var RxChampionship $championship */
        $car = RxCar::create($request->only('name', 'short_name'));
        \Notification::add('success', 'Car "'.$car->name.'" added');
        return \Redirect::route('rallycross.car.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  RxCar $car
     * @return \Illuminate\Http\Response
     */
    public function edit(RxCar $car)
    {
        return view('rallycross.car.edit')
            ->with('car', $car);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CarRequest $request
     * @param  RxCar $car
     * @return \Illuminate\Http\Response
     */
    public function update(CarRequest $request, RxCar $car)
    {
        $car->fill($request->only('name', 'short_name'))->save();
        \Event::fire(new CarUpdated($car));
        \Notification::add('success', 'Car "'.$car->name.'" updated');
        return \Redirect::route('rallycross.car.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RxCar $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(RxCar $car)
    {
        if ($car->entrants->count()) {
            \Notification::add('error', 'Car "'.$car->name.'" cannot be deleted - there are entrants that use it');
        } else {
            $car->delete();
            \Notification::add('success', 'Car "'.$car->name.'" deleted');
        }
        return \Redirect::route('rallycross.car.index');
    }
}
