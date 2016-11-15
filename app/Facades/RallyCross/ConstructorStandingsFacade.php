<?php

namespace App\Facades\RallyCross;

use App\Services\RallyCross\ConstructorStandings;
use \Illuminate\Support\Facades\Facade;

class ConstructorStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return ConstructorStandings::class; }
}