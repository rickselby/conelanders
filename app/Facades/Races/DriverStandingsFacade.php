<?php

namespace App\Facades\Races;

use App\Interfaces\Races\DriverStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class DriverStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverStandingsInterface::class; }
}