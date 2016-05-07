<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcPointsSystem;

use App\Http\Requests;
use App\Services\AssettoCorsa\DriverPoints;
use App\Services\AssettoCorsa\Results;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('assetto-corsa.validateRace')->only(['race', 'lapChart']);
    }

    public function index()
    {
        return view('assetto-corsa.standings.index')
            ->with('systems', AcPointsSystem::all());

    }

    public function system(AcPointsSystem $system)
    {
        return view('assetto-corsa.standings.system')
            ->with('system', $system)
            ->with('championships', AcChampionship::all()->sortBy('closes'));
    }

    public function championship(AcPointsSystem $system, AcChampionship $championship, DriverPoints $driverPoints, Results $resultsService)
    {
        $championship->load('races.entrants.championshipEntrant.driver.nation', 'entrants.driver.nation');
        $races = $championship->races()->get()->sortBy('time');
        return view('assetto-corsa.standings.championship')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('races', $races)
            ->with('points', \Positions::addEquals($driverPoints->forChampionship($system, $championship)))
            ->with('summary', $resultsService->summary($championship))
            ;
    }

    public function race(AcPointsSystem $system, $championship, $race, Results $resultsService)
    {
        $race = \Request::get('race');
        $race->load('entrants.championshipEntrant.driver.nation');
        return view('assetto-corsa.standings.race')
            ->with('system', $system)
            ->with('race', $race)
            ->with('qualifying', $resultsService->qualifying($race))
            ->with('results', $resultsService->race($race))
            ;
    }

    public function lapChart(AcPointsSystem $system, $championship, $race, Results $results)
    {
        $race = \Request::get('race');
        $content = $results->lapChart($race);
        $response = \Response::make($content);
        $response->header('Content-type',  'image/svg+xml');
        return $response;
    }

}
