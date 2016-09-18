<?php

namespace App\Http\Controllers\API\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Services\AssettoCorsa\Signups;

class SignupsController extends Controller
{
    public function current(Signups $signupsService)
    {
        return \Response::json($signupsService->getCurrent());
    }
}