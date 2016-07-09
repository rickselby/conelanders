<?php

namespace App\Facades\DirtRally;

use App\Interfaces\DirtRally\DriverPointsInterface;
use \Illuminate\Support\Facades\Facade;

class DriverPointsFacade extends Facade {
    protected static function getFacadeAccessor() { return DriverPointsInterface::class; }
}