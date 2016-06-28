<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirtRally\ChampionshipSeasonRequest;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtSeason;

class ChampionshipSeasonController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:dirt-rally-admin');
        $this->middleware('dirt-rally.validateSeason', ['only' => ['show', 'edit', 'update', 'destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param DirtChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function create(DirtChampionship $championship)
    {
        return view('dirt-rally.season.create')
            ->with('championship', $championship);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipSeasonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipSeasonRequest $request, DirtChampionship $championship)
    {
        /** @var DirtSeason $season */
        $season = $championship->seasons()->create($request->all());
        \Notification::add('success', 'Season "'.$season->name.'" created');
        return \Redirect::route('dirt-rally.championship.season.show', [$championship, $season]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function show($championship, $season)
    {
        return view('dirt-rally.season.show')
            ->with('season', \Request::get('season'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function edit($championship, $season)
    {
        return view('dirt-rally.season.edit')
            ->with('season', \Request::get('season'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipSeasonRequest $request
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipSeasonRequest $request, $championship, $season)
    {
        $season = \Request::get('season');
        $season->fill($request->all());
        $season->save();
        \Notification::add('success', 'Season "'.$season->name.'" updated');
        return \Redirect::route('dirt-rally.championship.season.show', [$championship, $season]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function destroy($championship, $season)
    {
        $season = \Request::get('season');
        if ($season->events->count()) {
            \Notification::add('error', 'Season "'.$season->name.'" cannot be deleted - there are events assigned to it');
            return \Redirect::route('dirt-rally.championship.season.show', [$championship, $season]);
        } else {
            $season->delete();
            \Notification::add('success', 'Season "'.$season->name.'" deleted');
            return \Redirect::route('dirt-rally.championship.show', $championship);
        }
    }
}
