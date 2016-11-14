<?php

namespace App\Http\Controllers;

use App\Events\DriverUpdated;
use App\Models\Driver;
use App\Models\Nation;
use Illuminate\Http\Request;

use App\Http\Requests;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:driver-admin', ['except' =>
            ['index', 'show']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('driver.index')
            ->with('drivers', Driver::orderBy('name')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  Driver $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        return view('driver.show')
            ->with('driver', $driver)
            ->with('dirtResults', \DirtRallyResults::forDriver($driver))
            ->with('acResults', \RacesResults::forDriver($driver));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Driver $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        return view('driver.edit')
            ->with('driver', $driver)
            ->with('nations', Nation::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $driver->fill($request->all());
        $driver->save();
        \Event::fire(new DriverUpdated($driver));
        return \Redirect::route('driver.index');
    }
}
