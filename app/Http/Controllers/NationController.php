<?php

namespace App\Http\Controllers;

use App\Models\Nation;
use Illuminate\Http\Request;
use App\Http\Requests\NationRequest;

class NationController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:nation-admin', ['except' =>
            ['image']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('nation.index')
            ->with('nations', Nation::orderBy('name')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('nation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NationRequest $request)
    {
        $nation = Nation::create($request->all());
        \Notification::add('success', 'Nation "'.$nation->name.'" created');
        return \Redirect::route('nation.index', $nation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Nation $nation
     * @return \Illuminate\Http\Response
     */
    public function edit(Nation $nation)
    {
        return view('nation.edit')
            ->with('nation', $nation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Nation $nation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nation $nation)
    {
        $nation->fill($request->all());
        $nation->save();

        \Notification::add('success', 'Nation "'.$nation->name.'" updated');
        return \Redirect::route('nation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Nation $nation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nation $nation)
    {
        if ($nation->drivers->count()) {
            \Notification::add('error', 'Nation "'.$nation->name.'" cannot be deleted - there are drivers assigned to it');
            return \Redirect::route('nation.index');
        } else {
            $nation->delete();
            \Notification::add('success', 'Nation "'.$nation->name.'" deleted');
            return \Redirect::route('nation.index');
        }
    }

    /**
     * Get the flag for a nation
     * @param Nation $nation
     * @return \Illuminate\Http\Response
     */
    public function image(Nation $nation)
    {
        return response()->file(\Nations::getFlagPath($nation));
    }
}
