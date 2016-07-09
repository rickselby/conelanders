<?php

namespace App\Facades\DirtRally;

use App\Services\DirtRally\ImportCSV;
use \Illuminate\Support\Facades\Facade;

class ImportCSVFacade extends Facade {
    protected static function getFacadeAccessor() { return ImportCSV::class; }
}