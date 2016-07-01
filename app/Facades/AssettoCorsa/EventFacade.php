<?php

namespace App\Facades\AssettoCorsa;

use App\Interfaces\AssettoCorsa\EventInterface;
use \Illuminate\Support\Facades\Facade;

class EventFacade extends Facade {
    protected static function getFacadeAccessor() { return EventInterface::class; }
}