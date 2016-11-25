<?php

namespace App\Facades\RallyCross;

use App\Services\RallyCross\Session;
use \Illuminate\Support\Facades\Facade;

class SessionFacade extends Facade {
    protected static function getFacadeAccessor() { return Session::class; }
}