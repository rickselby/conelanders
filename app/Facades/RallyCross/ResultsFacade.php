<?php

namespace App\Facades\Races;

use App\Interfaces\Races\ResultsInterface;
use \Illuminate\Support\Facades\Facade;

class ResultsFacade extends Facade {
    protected static function getFacadeAccessor() { return ResultsInterface::class; }
}