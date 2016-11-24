<?php

namespace App\Facades\RallyCross;

use App\Interfaces\RallyCross\ConstructorStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class ConstructorStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return ConstructorStandingsInterface::class; }
}