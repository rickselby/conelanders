<?php

namespace App\Facades\Races;

use App\Services\Races\Import;
use \Illuminate\Support\Facades\Facade;

class ImportFacade extends Facade {
    protected static function getFacadeAccessor() { return Import::class; }
}