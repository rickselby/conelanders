<?php

namespace App\Services\Facades\DirtRally;

use App\Services\DirtRally\StageTime;
use \Illuminate\Support\Facades\Facade;

class StageTimeFacade extends Facade {
    protected static function getFacadeAccessor() { return StageTime::class; }
}