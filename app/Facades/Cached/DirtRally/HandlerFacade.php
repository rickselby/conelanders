<?php

namespace App\Facades\Cached\DirtRally;

use App\Services\Cached\DirtRally\Handler;
use \Illuminate\Support\Facades\Facade;

class HandlerFacade extends Facade {
    protected static function getFacadeAccessor() { return Handler::class; }
}