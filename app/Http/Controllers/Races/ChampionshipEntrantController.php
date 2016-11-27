<?php

namespace App\Http\Controllers\Races;

use App\Events\Races\ChampionshipEntrantsUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Races\ChampionshipEntrantRequest;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesChampionshipEntrant;

class ChampionshipEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('races.validateEntrant')->only(['edit', 'update', 'destroy']);
        $this->middleware('can:manage-entrants,championship')->except(['css']);
    }

    public function css(RacesChampionship $championship)
    {
        $championship->load('entrants');
        return response()
            ->view('races.championship.css', ['championship' => $championship])
            ->header('Content-Type', 'text/css');
    }

    public function index(RacesChampionship $championship)
    {
        return view('races.championship.entrant.index')
            ->with('championship', $championship);
    }

    public function create(RacesChampionship $championship)
    {
        return view('races.championship.entrant.create')
            ->with('championship', $championship);
    }

    public function store(ChampionshipEntrantRequest $request, RacesChampionship $championship)
    {
        $championship->entrants()->create($request->all());
        \Event::fire(new ChampionshipEntrantsUpdated($championship));
        \Notification::add('success', 'Entrant added');
        return \Redirect::route('races.championship.entrant.index', $championship);
    }

    public function edit(RacesChampionship $championship, RacesChampionshipEntrant $entrant)
    {
        return view('races.championship.entrant.edit')
            ->with('championship', $championship)
            ->with('entrant', $entrant);
    }

    public function update(ChampionshipEntrantRequest $request, RacesChampionship $championship, RacesChampionshipEntrant $entrant)
    {
        $entrant->fill($request->except('driver_id'))->save();
        \Event::fire(new ChampionshipEntrantsUpdated($championship));
        \Notification::add('success', 'Entrant updated');
        return \Redirect::route('races.championship.entrant.index', $championship);
    }

    public function destroy(RacesChampionship $championship, RacesChampionshipEntrant $entrant)
    {
        if ($entrant->entries->count()) {
            \Notification::add('error', 'Entrant cannot be deleted - they have entries in sessions');
        } else {
            $entrant->delete();
            \Notification::add('success', 'Entrant deleted');
        }
        return \Redirect::route('races.championship.entrant.index', $championship);
    }
}
