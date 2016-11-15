<?php

namespace App\Facades\Cached\Races;

use App\Services\Cached\Races\Handler;
use \Illuminate\Support\Facades\Facade;

class HandlerFacade extends Facade {
    protected static function getFacadeAccessor() { return Handler::class; }
}