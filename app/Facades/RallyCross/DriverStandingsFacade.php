<?php

namespace App\Facades\RallyCross;

use App\Interfaces\RallyCross\DriverStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class DriverStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverStandingsInterface::class; }
}