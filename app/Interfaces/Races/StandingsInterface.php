<?php

namespace App\Interfaces\Races;

use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;

interface StandingsInterface
{
    /**
     * Get results summary for an event
     * @param RacesEvent $event
     * @return mixed
     */
    public function eventSummary(RacesEvent $event);

    /**
     * Get results for an event
     * @param RacesEvent $event
     * @return mixed
     */
    public function event(RacesEvent $event);

    /**
     * Get results for a championship
     * @param RacesChampionship $championship
     * @return mixed
     */
    public function championship(RacesChampionship $championship);

    /**
     * Get the list of possible options for scoring this championship
     * @return mixed
     */
    public function getOptions();

}