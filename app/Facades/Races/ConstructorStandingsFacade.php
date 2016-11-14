<?php

namespace App\Facades\Races;

use App\Interfaces\Races\ConstructorStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class ConstructorStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return ConstructorStandingsInterface::class; }
}