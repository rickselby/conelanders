<?php

namespace App\Services\Facades;

use App\Services\DriverPoints;
use \Illuminate\Support\Facades\Facade;

class DriverPointsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverPoints::class; }
}