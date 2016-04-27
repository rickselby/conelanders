<?php

namespace App\Services\Facades\DirtRally;

use App\Services\DirtRally\NationPoints;
use \Illuminate\Support\Facades\Facade;

class NationPointsFacade extends Facade {

    protected static function getFacadeAccessor() { return NationPoints::class; }

}