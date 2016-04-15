<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonRequest;
use App\Models\Championship;
use App\Models\Season;

class ChampionshipSeasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['show']]);
        $this->middleware('validateSeason', ['only' => ['show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Championship $championship
     * @return \Illuminate\Http\Response
     */
    public function create(Championship $championship)
    {
        return view('season.create')
            ->with('championship', $championship);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SeasonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeasonRequest $request, Championship $championship)
    {
        /** @var Season $season */
        $season = Season::create($request->all());
        $championship->seasons()->save($season);
        \Notification::add('success', 'Season "'.$season->name.'" created');
        return \Redirect::route('championship.season.show', [$championship, $season->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $championship
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function show($championship, Season $season)
    {
        return view('season.show')
            ->with('season', $season);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $championship
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function edit($championship, Season $season)
    {
        return view('season.edit')
            ->with('season', $season);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SeasonRequest $request
     * @param  int $championship
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonRequest $request, $championship, Season $season)
    {
        $season->fill($request->all());
        $season->save();
        \Notification::add('success', 'Season "'.$season->name.'" updated');
        return \Redirect::route('championship.season.show', [$championship, $season->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $championship
     * @param  Season $season
     * @return \Illuminate\Http\Response
     */
    public function destroy($championship, Season $season)
    {
        if ($season->events->count()) {
            \Notification::add('error', 'Season "'.$season->name.'" cannot be deleted - there are events assigned to it');
            return \Redirect::route('championship.season.show', [$championship, $season->id]);
        } else {
            $season->delete();
            \Notification::add('success', 'Season "'.$season->name.'" deleted');
            return \Redirect::route('championship.show', [$championship]);
        }
    }
}
