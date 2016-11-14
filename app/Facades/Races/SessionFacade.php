<?php

namespace App\Facades\Races;

use App\Services\Races\Session;
use \Illuminate\Support\Facades\Facade;

class SessionFacade extends Facade {
    protected static function getFacadeAccessor() { return Session::class; }
}