<?php

namespace App\Services\Facades\DirtRally;

use App\Services\DirtRally\Positions;
use \Illuminate\Support\Facades\Facade;

class PositionsFacade extends Facade {
    protected static function getFacadeAccessor() { return Positions::class; }
}