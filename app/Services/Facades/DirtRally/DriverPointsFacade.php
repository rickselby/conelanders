<?php

namespace App\Services\Facades\DirtRally;

use App\Services\DirtRally\DriverPoints;
use \Illuminate\Support\Facades\Facade;

class DriverPointsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverPoints::class; }
}