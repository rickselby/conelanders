<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Events\AssettoCorsa\ChampionshipEntrantsUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssettoCorsa\ChampionshipEntrantRequest;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcChampionshipEntrant;

class ChampionshipEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:assetto-corsa-admin')
        ->except(['css']);
    }

    public function css(AcChampionship $championship)
    {
        $championship->load('entrants');
        return response()
            ->view('assetto-corsa.championship.entrant.css', ['championship' => $championship])
            ->header('Content-Type', 'text/css');
    }

    public function index(AcChampionship $championship)
    {
        return view('assetto-corsa.championship.entrant.index')
            ->with('championship', $championship);
    }

    public function create(AcChampionship $championship)
    {
        return view('assetto-corsa.championship.entrant.create')
            ->with('championship', $championship);
    }

    public function store(ChampionshipEntrantRequest $request, AcChampionship $championship)
    {
        $championship->entrants()->create($request->all());
        \Event::fire(new ChampionshipEntrantsUpdated($championship));
        \Notification::add('success', 'Entrant added');
        return \Redirect::route('assetto-corsa.championship.entrant.index', $championship);
    }

    public function edit(AcChampionship $championship, AcChampionshipEntrant $entrant)
    {
        return view('assetto-corsa.championship.entrant.edit')
            ->with('championship', $championship)
            ->with('entrant', $entrant);
    }

    public function update(ChampionshipEntrantRequest $request, AcChampionship $championship, AcChampionshipEntrant $entrant)
    {
        $entrant->fill($request->except('driver_id'))->save();
        \Event::fire(new ChampionshipEntrantsUpdated($championship));
        \Notification::add('success', 'Entrant updated');
        return \Redirect::route('assetto-corsa.championship.entrant.index', $championship);
    }

    public function destroy(AcChampionship $championship, AcChampionshipEntrant $entrant)
    {
        if ($entrant->entries->count()) {
            \Notification::add('error', 'Entrant cannot be deleted - they have entries in sessions');
        } else {
            $entrant->delete();
            \Notification::add('success', 'Entrant deleted');
        }
        return \Redirect::route('assetto-corsa.championship.entrant.index', $championship);
    }
}
