<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
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
        return view('season.show')
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
        return view('season.edit')
            ->with('season', \Request::get('season'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SeasonRequest $request
     * @param  string $championship
     * @param  string $season
     * @return \Illuminate\Http\Response
     */
    public function update(SeasonRequest $request, $championship, $season)
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
