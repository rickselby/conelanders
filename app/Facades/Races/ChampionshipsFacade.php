<?php

namespace App\Facades\Races;

use App\Interfaces\Races\ChampionshipInterface;
use \Illuminate\Support\Facades\Facade;

class ChampionshipsFacade extends Facade {
    protected static function getFacadeAccessor() { return ChampionshipInterface::class; }
}