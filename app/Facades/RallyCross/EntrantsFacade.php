<?php

namespace App\Facades\RallyCross;

use App\Services\RallyCross\Entrants;
use \Illuminate\Support\Facades\Facade;

class EntrantsFacade extends Facade {
    protected static function getFacadeAccessor() { return Entrants::class; }
}