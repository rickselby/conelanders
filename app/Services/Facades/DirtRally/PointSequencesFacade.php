<?php

namespace App\Services\Facades\DirtRally;

use App\Services\DirtRally\PointSequences;
use \Illuminate\Support\Facades\Facade;

class PointSequencesFacade extends Facade {
    protected static function getFacadeAccessor() { return PointSequences::class; }
}