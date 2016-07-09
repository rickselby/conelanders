<?php

namespace App\Facades\DirtRally;

use App\Interfaces\DirtRally\ResultsInterface;
use \Illuminate\Support\Facades\Facade;

class ResultsFacade extends Facade {
    protected static function getFacadeAccessor() { return ResultsInterface::class; }
}