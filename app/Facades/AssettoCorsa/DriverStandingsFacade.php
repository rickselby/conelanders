<?php

namespace App\Facades\AssettoCorsa;

use App\Interfaces\AssettoCorsa\DriverStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class DriverStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverStandingsInterface::class; }
}