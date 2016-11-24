<?php

namespace App\Facades\RallyCross;

use App\Interfaces\RallyCross\ChampionshipInterface;
use \Illuminate\Support\Facades\Facade;

class ChampionshipsFacade extends Facade {
    protected static function getFacadeAccessor() { return ChampionshipInterface::class; }
}