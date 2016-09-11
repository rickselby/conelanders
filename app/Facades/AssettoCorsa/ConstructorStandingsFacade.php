<?php

namespace App\Facades\AssettoCorsa;

use App\Interfaces\AssettoCorsa\ConstructorStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class ConstructorStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return ConstructorStandingsInterface::class; }
}