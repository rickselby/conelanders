<?php

namespace App\Facades\AssettoCorsa;

use App\Interfaces\AssettoCorsa\ResultsInterface;
use \Illuminate\Support\Facades\Facade;

class ResultsFacade extends Facade {
    protected static function getFacadeAccessor() { return ResultsInterface::class; }
}