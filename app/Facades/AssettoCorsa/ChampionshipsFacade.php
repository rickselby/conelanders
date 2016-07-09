<?php

namespace App\Facades\AssettoCorsa;

use App\Services\AssettoCorsa\Championships;
use \Illuminate\Support\Facades\Facade;

class ChampionshipsFacade extends Facade {
    protected static function getFacadeAccessor() { return Championships::class; }
}