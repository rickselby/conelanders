<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcRace;
use Symfony\Component\HttpFoundation\Request;

class Entrants
{

    public function updateCars(Request $request, AcRace $race)
    {
        foreach($race->entrants AS $entrant) {
            $entrant->car = $request->get('car')[$entrant->id];
            $entrant->save();
        }
    }

    public function updateNumbersAndColours(Request $request, AcChampionship $championship)
    {
        foreach($championship->entrants AS $entrant) {
            $entrant->number = $request->get('number')[$entrant->id];
            $entrant->colour = $request->get('colour')[$entrant->id];
            $entrant->colour2 = $request->get('colour2')[$entrant->id];
            $entrant->rookie = isset($request->get('rookie')[$entrant->id]);
            $entrant->save();
        }
    }

}