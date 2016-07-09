<?php

namespace App\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\Import;
use \Illuminate\Support\Facades\Facade;

class ImportFacade extends Facade {
    protected static function getFacadeAccessor() { return Import::class; }
}