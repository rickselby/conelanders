<?php

namespace App\Services\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\Entrants;
use \Illuminate\Support\Facades\Facade;

class EntrantsFacade extends Facade {
    protected static function getFacadeAccessor() { return Entrants::class; }
}