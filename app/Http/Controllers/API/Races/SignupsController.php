<?php

namespace App\Http\Controllers\API\Races;

use App\Http\Controllers\Controller;
use App\Models\Races\RacesChampionship;
use App\Services\Races\Signups;

class SignupsController extends Controller
{
    public function current(Signups $signupsService)
    {
        return \Response::json($signupsService->getCurrent());
    }

    public function championship(RacesChampionship $championship, Signups $signupService)
    {
        return \Response::json($signupService->getForChampionship($championship));
    }
}