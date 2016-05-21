<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;

use App\Http\Requests;
use App\Services\AssettoCorsa\DriverPoints;
use App\Services\AssettoCorsa\Results;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('assetto-corsa.validateRace')->only(['race', 'lapChart']);
    }

    public function championship(AcChampionship $championship, DriverPoints $driverPoints, Results $resultsService)
    {
        $championship->load('races.entrants.championshipEntrant.driver.nation', 'entrants.driver.nation');
        $races = $championship->races()->get()->sortBy('time');
        return view('assetto-corsa.standings.championship')
            ->with('championship', $championship)
            ->with('races', $races)
            ->with('points', \Positions::addEquals($driverPoints->forChampionship($championship)))
            ->with('summary', $resultsService->summary($championship));
    }

    public function race($championship, $race, Results $resultsService)
    {
        $race = \Request::get('race');
        $race->load('entrants.championshipEntrant.driver.nation');
        return view('assetto-corsa.standings.race')
            ->with('race', $race)
            ->with('qualifying', $resultsService->qualifying($race))
            ->with('results', $resultsService->race($race))
            ;
    }

    public function lapChart($championship, $race, Results $resultsService)
    {
        $race = \Request::get('race');
        $content = $resultsService->lapChart($race);
        $response = \Response::make($content);
        $response->header('Content-type',  'image/svg+xml');
        return $response;
    }

}
