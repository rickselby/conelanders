<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Events\AssettoCorsa\ChampionshipEntrantsUpdated;
use App\Events\AssettoCorsa\ChampionshipTeamsUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssettoCorsa\ChampionshipEntrantRequest;
use App\Http\Requests\AssettoCorsa\ChampionshipTeamRequest;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcChampionshipEntrant;
use App\Models\AssettoCorsa\AcTeam;

class ChampionshipTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:assetto-corsa-admin');
    }

    public function index(AcChampionship $championship)
    {
        return view('assetto-corsa.championship.team.index')
            ->with('championship', $championship);
    }

    public function create(AcChampionship $championship)
    {
        return view('assetto-corsa.championship.team.create')
            ->with('championship', $championship);
    }

    public function store(ChampionshipTeamRequest $request, AcChampionship $championship)
    {
        $championship->teams()->create($request->all());
        \Event::fire(new ChampionshipTeamsUpdated($championship));
        \Notification::add('success', 'Team added');
        return \Redirect::route('assetto-corsa.championship.team.index', $championship);
    }

    public function edit(AcChampionship $championship, AcTeam $team)
    {
        return view('assetto-corsa.championship.team.edit')
            ->with('championship', $championship)
            ->with('team', $team);
    }

    public function update(ChampionshipTeamRequest $request, AcChampionship $championship, AcTeam $team)
    {
        $team->fill($request->all())->save();
        \Event::fire(new ChampionshipTeamsUpdated($championship));
        \Notification::add('success', 'Team updated');
        return \Redirect::route('assetto-corsa.championship.team.index', $championship);
    }

    public function destroy(AcChampionship $championship, AcTeam $team)
    {
        if ($team->entrants->count()) {
            \Notification::add('error', 'Team cannot be deleted - it has members');
        } else {
            $team->delete();
            \Notification::add('success', 'Team deleted');
        }
        return \Redirect::route('assetto-corsa.championship.team.index', $championship);
    }

}
