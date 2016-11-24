<?php

namespace App\Facades\RallyCross;

use App\Interfaces\RallyCross\ResultsInterface;
use \Illuminate\Support\Facades\Facade;

class ResultsFacade extends Facade {
    protected static function getFacadeAccessor() { return ResultsInterface::class; }
}