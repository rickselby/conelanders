<?php

namespace App\Services\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\Race;
use \Illuminate\Support\Facades\Facade;

class RaceFacade extends Facade {
    protected static function getFacadeAccessor() { return Race::class; }
}