<?php

namespace App\Interfaces\DirtRally;

use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;

interface TimesInterface
{
    /**
     * Get total times for a single event
     * @param DirtEvent $event
     * @return mixed
     */
    public function forEvent(DirtEvent $event);

    /**
     * Get total times for a single season
     * @param DirtSeason $season
     * @return mixed
     */
    public function forSeason(DirtSeason $season);

    /**
     * Get total times for a championship
     * @param DirtChampionship $championship
     * @return mixed
     */
    public function overall(DirtChampionship $championship);

}