<?php

namespace App\Services\Facades;

use App\Services\NationPoints;
use \Illuminate\Support\Facades\Facade;

class NationPointsFacade extends Facade {

    protected static function getFacadeAccessor() { return NationPoints::class; }

}