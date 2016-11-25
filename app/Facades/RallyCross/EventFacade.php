<?php

namespace App\Facades\RallyCross;

use App\Interfaces\RallyCross\EventInterface;
use \Illuminate\Support\Facades\Facade;

class EventFacade extends Facade {
    protected static function getFacadeAccessor() { return EventInterface::class; }
}