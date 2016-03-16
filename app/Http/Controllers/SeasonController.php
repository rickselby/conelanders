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
     * @param SeasonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonRequest $request)
    {
        /** @var Season $season */
        $season = Season::create($request->all());
        \Notification::add('success', 'Season "'.$season->name.'" created');
        return \Redirect::route('season.show', ['season_id' => $season->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $season_id
     * @return \Illuminate\Http\Response
     */
    public function show($season_id)
    {
        return view('season.show')
            ->with('season', Season::with('events')->find($season_id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $season_id
     * @return \Illuminate\Http\Response
     */
    public function edit($season_id)
    {
        return view('season.edit')
            ->with('season', Season::find($season_id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SeasonRequest $request
     * @param  int  $season_id
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonRequest $request, $season_id)
    {
        $season = Season::find($season_id);
        $season->fill($request->all());
        $season->save();

        \Notification::add('success', 'Season "'.$season->name.'" updated');
        return \Redirect::route('season.show', ['season_id' => $season_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $season_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($season_id)
    {
        $season = Season::with('events')->find($season_id);
        if ($season->events->count()) {
            \Notification::add('error', 'Season "'.$season->name.'" cannot be deleted - there are events assigned to it');
            return \Redirect::route('season.show', ['season_id' => $season_id]);
        } else {
            $season->delete();
            \Notification::add('success', 'Season "'.$season->name.'" deleted');
            return \Redirect::route('season.index');
        }
    }
}
