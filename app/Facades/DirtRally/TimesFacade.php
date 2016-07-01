<?php

namespace App\Facades\DirtRally;

use App\Services\DirtRally\Times;
use \Illuminate\Support\Facades\Facade;

class TimesFacade extends Facade {
    protected static function getFacadeAccessor() { return Times::class; }
}