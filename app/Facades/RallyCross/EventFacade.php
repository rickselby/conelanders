<?php

namespace App\Facades\RallyCross;

use App\Services\RallyCross\Event;
use \Illuminate\Support\Facades\Facade;

class EventFacade extends Facade {
    protected static function getFacadeAccessor() { return Event::class; }
}