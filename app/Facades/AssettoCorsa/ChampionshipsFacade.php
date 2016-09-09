<?php

namespace App\Facades\AssettoCorsa;

use App\Interfaces\AssettoCorsa\ChampionshipInterface;
use \Illuminate\Support\Facades\Facade;

class ChampionshipsFacade extends Facade {
    protected static function getFacadeAccessor() { return ChampionshipInterface::class; }
}