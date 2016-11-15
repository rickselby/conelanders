<?php

namespace App\Facades\Races;

use App\Interfaces\Races\EventInterface;
use \Illuminate\Support\Facades\Facade;

class EventFacade extends Facade {
    protected static function getFacadeAccessor() { return EventInterface::class; }
}