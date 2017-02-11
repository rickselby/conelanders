<?php

namespace App\Facades\Cached\AcHotlap;

use App\Services\Cached\AcHotlap\Handler;
use \Illuminate\Support\Facades\Facade;

class HandlerFacade extends Facade {
    protected static function getFacadeAccessor() { return Handler::class; }
}