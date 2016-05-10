<?php

namespace App\Services\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\PointsSystems;
use \Illuminate\Support\Facades\Facade;

class PointsSystemsFacade extends Facade {
    protected static function getFacadeAccessor() { return PointsSystems::class; }
}