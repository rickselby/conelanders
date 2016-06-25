<?php

namespace App\Services\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\Event;
use \Illuminate\Support\Facades\Facade;

class EventFacade extends Facade {
    protected static function getFacadeAccessor() { return Event::class; }
}