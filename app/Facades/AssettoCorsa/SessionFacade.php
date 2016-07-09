<?php

namespace App\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\Session;
use \Illuminate\Support\Facades\Facade;

class SessionFacade extends Facade {
    protected static function getFacadeAccessor() { return Session::class; }
}