<?php

namespace App\Facades\Races;

use App\Services\Races\Entrants;
use \Illuminate\Support\Facades\Facade;

class EntrantsFacade extends Facade {
    protected static function getFacadeAccessor() { return Entrants::class; }
}