<?php

namespace App\Facades\Cached\RallyCross;

use App\Services\Cached\RallyCross\Handler;
use \Illuminate\Support\Facades\Facade;

class HandlerFacade extends Facade {
    protected static function getFacadeAccessor() { return Handler::class; }
}