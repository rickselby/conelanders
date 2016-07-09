<?php

namespace App\Facades\DirtRally;

use App\Interfaces\DirtRally\NationPointsInterface;
use \Illuminate\Support\Facades\Facade;

class NationPointsFacade extends Facade {

    protected static function getFacadeAccessor() { return NationPointsInterface::class; }

}