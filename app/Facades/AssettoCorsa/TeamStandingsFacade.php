<?php

namespace App\Facades\AssettoCorsa;

use App\Interfaces\AssettoCorsa\TeamStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class TeamStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return TeamStandingsInterface::class; }
}