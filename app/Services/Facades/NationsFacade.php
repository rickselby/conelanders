<?php

namespace App\Services\Facades;

use App\Services\Nations;
use \Illuminate\Support\Facades\Facade;

class NationsFacade extends Facade {

    protected static function getFacadeAccessor() { return Nations::class; }

}