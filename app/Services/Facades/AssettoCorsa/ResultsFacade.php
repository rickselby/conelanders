<?php

namespace App\Services\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\Results;
use \Illuminate\Support\Facades\Facade;

class ResultsFacade extends Facade {
    protected static function getFacadeAccessor() { return Results::class; }
}