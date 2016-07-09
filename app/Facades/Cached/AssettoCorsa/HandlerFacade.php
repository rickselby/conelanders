<?php

namespace App\Facades\Cached\AssettoCorsa;

use App\Services\Cached\AssettoCorsa\Handler;
use \Illuminate\Support\Facades\Facade;

class HandlerFacade extends Facade {
    protected static function getFacadeAccessor() { return Handler::class; }
}