<?php

namespace App\Services\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\DriverPoints;
use \Illuminate\Support\Facades\Facade;

class DriverPointsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverPoints::class; }
}