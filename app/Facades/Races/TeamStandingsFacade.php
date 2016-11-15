<?php

namespace App\Facades\Races;

use App\Interfaces\Races\TeamStandingsInterface;
use \Illuminate\Support\Facades\Facade;

class TeamStandingsFacade extends Facade {
    protected static function getFacadeAccessor() { return TeamStandingsInterface::class; }
}