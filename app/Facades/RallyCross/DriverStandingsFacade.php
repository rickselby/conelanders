<?php

namespace App\Facades\RallyCross;

use App\Services\RallyCross\DriverStandings;
use \Illuminate\Support\Facades\Facade;

class DriverStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverStandings::class; }
}