<?php

namespace App\Http\Controllers;

use App\Models\Driver;
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
            ->with('acResults', \ACResults::forDriver($driver));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
