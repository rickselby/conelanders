<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\ChampionshipEntrantsUpdated;
use App\Events\Races\ChampionshipTeamsUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\ChampionshipEntrantRequest;
use App\Http\Requests\Races\ChampionshipTeamRequest;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesChampionshipEntrant;
use App\Models\Races\RacesTeam;

class ChampionshipTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:races-admin');
    }

    public function index(RacesChampionship $championship)
    {
        return view('races.championship.team.index')
            ->with('championship', $championship);
    }

    public function create(RacesChampionship $championship)
    {
        return view('races.championship.team.create')
            ->with('championship', $championship);
    }

    public function store(ChampionshipTeamRequest $request, RacesChampionship $championship)
    {
        $championship->teams()->create($request->all());
        \Event::fire(new ChampionshipTeamsUpdated($championship));
        \Notification::add('success', 'Team added');
        return \Redirect::route('races.championship.team.index', $championship);
    }

    public function edit(RacesChampionship $championship, RacesTeam $team)
    {
        return view('races.championship.team.edit')
            ->with('championship', $championship)
            ->with('team', $team);
    }

    public function update(ChampionshipTeamRequest $request, RacesChampionship $championship, RacesTeam $team)
    {
        $team->fill($request->all())->save();
        \Event::fire(new ChampionshipTeamsUpdated($championship));
        \Notification::add('success', 'Team updated');
        return \Redirect::route('races.championship.team.index', $championship);
    }

    public function destroy(RacesChampionship $championship, RacesTeam $team)
    {
        if ($team->entrants->count()) {
            \Notification::add('error', 'Team cannot be deleted - it has members');
        } else {
            $team->delete();
            \Notification::add('success', 'Team deleted');
        }
        return \Redirect::route('races.championship.team.index', $championship);
    }

}
