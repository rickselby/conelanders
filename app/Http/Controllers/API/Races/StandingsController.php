<?php

namespace App\Http\Controllers\API\Races;

use App\Http\Controllers\Controller;
use App\Models\Races\RacesChampionship;
use App\Services\Races\DriverStandings;

class StandingsController extends Controller
{
    public function championship(RacesChampionship $championship, DriverStandings $standingsService)
    {
        $standings = [];

        foreach($standingsService->championship($championship) AS $entrant) {
            $standings[] = [
                'position' => $entrant['position'],
                'guid' => $entrant['entrant']->driver->steam_id,
            ];
        }

        return \Response::json($standings);
    }
}