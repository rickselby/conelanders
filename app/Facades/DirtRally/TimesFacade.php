<?php

namespace App\Facades\DirtRally;

use App\Interfaces\DirtRally\TimesInterface;
use \Illuminate\Support\Facades\Facade;

class TimesFacade extends Facade {
    protected static function getFacadeAccessor() { return TimesInterface::class; }
}