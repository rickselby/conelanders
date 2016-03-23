<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonRequest;
use App\Models\Season;

class SeasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' =>
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
        return view('season.index')
            ->with('seasons', Season::with('events')->get()->sortBy('endDate'));
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
     * @param SeasonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonRequest $request)
    {
        /** @var Season $season */
        $season = Season::create($request->all());
        \Notification::add('success', 'Season "'.$season->name.'" created');
        return \Redirect::route('season.show', [$season->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function show(Season $season)
    {
        return view('season.show')
            ->with('season', $season);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function edit(Season $season)
    {
        return view('season.edit')
            ->with('season', $season);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SeasonRequest $request
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonRequest $request, Season $season)
    {
        $season->fill($request->all());
        $season->save();

        \Notification::add('success', 'Season "'.$season->name.'" updated');
        return \Redirect::route('season.show', [$season->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function destroy(Season $season)
    {
        if ($season->events->count()) {
            \Notification::add('error', 'Season "'.$season->name.'" cannot be deleted - there are events assigned to it');
            return \Redirect::route('season.show', [$season->id]);
        } else {
            $season->delete();
            \Notification::add('success', 'Season "'.$season->name.'" deleted');
            return \Redirect::route('season.index');
        }
    }
}
