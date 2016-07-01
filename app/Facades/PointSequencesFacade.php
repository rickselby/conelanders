<?php

namespace App\Facades;

use App\Services\PointSequences;
use \Illuminate\Support\Facades\Facade;

class PointSequencesFacade extends Facade {
    protected static function getFacadeAccessor() { return PointSequences::class; }
}