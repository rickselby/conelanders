<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Events\AssettoCorsa\ChampionshipEntrantsUpdated;
use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;
use Illuminate\Http\Request;

class ChampionshipEntrantController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:assetto-corsa-admin');
    }

    public function index(AcChampionship $championship)
    {
        $championship->load('entrants.driver.nation');
        return view('assetto-corsa.entrants.index')
            ->with('championship', $championship);
    }

    public function update(Request $request, AcChampionship $championship)
    {
        \ACEntrants::updateEntrantDetails($request, $championship);
        \Event::fire(new ChampionshipEntrantsUpdated($championship));
        \Notification::add('success', 'Entrants updated');
        return \Redirect::route('assetto-corsa.championship.entrants.index', $championship);
    }
}
