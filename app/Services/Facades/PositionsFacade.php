<?php

namespace App\Services\Facades;

use App\Services\Positions;
use \Illuminate\Support\Facades\Facade;

class PositionsFacade extends Facade {
    protected static function getFacadeAccessor() { return Positions::class; }
}