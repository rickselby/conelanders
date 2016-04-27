<?php

namespace App\Services\Facades\DirtRally;

use App\Services\DirtRally\ImportDirt;
use \Illuminate\Support\Facades\Facade;

class ImportDirtFacade extends Facade {
    protected static function getFacadeAccessor() { return ImportDirt::class; }
}