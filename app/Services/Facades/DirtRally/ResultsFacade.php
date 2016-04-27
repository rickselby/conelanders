<?php

namespace App\Services\Facades\DirtRally;

use App\Services\DirtRally\Results;
use \Illuminate\Support\Facades\Facade;

class ResultsFacade extends Facade {
    protected static function getFacadeAccessor() { return Results::class; }
}