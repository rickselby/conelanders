<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Season;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('season.index')
            ->with('seasons', Season::with('events')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('season.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $season = Season::create($request->all());
        \Notification::add('success', $season->name.' created');
        return \Redirect::route('season.show', ['id' => $season->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('season.show')
            ->with('season', Season::with('events')->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('season.edit')
            ->with('season', Season::find($id));
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
        $season = Season::find($id);
        $season->fill($request->all());
        $season->save();

        \Notification::add('success', $season->name.' updated');
        return \Redirect::route('season.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $season = Season::with('events')->find($id);
        if ($season->events->count()) {
            \Notification::add('error', $season->name.' cannot be deleted - there are events assigned to it');
            return \Redirect::route('season.show', ['id' => $id]);
        } else {
            $season->delete();
            \Notification::add('success', $season->name.' deleted');
            return \Redirect::route('season.index');
        }
    }
}
