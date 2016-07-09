<?php

namespace App\Facades\DirtRally;

use App\Services\DirtRally\Events;
use \Illuminate\Support\Facades\Facade;

class EventsFacade extends Facade {
    protected static function getFacadeAccessor() { return Events::class; }
}