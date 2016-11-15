<?php

namespace App\Facades\Races;

use App\Interfaces\Races\CategoriesInterface;
use \Illuminate\Support\Facades\Facade;

class CategoriesFacade extends Facade {
    protected static function getFacadeAccessor() { return CategoriesInterface::class; }
}