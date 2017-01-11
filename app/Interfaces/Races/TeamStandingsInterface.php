<?php

namespace App\Interfaces\Races;

use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;

interface TeamStandingsInterface
{
    /**
     * Get results summary for an event
     * @param RacesEvent $event
     * @return mixed
     */
    public function eventSummary(RacesEvent $event, $teamSize);

    /**
     * Get results for an event
     * @param RacesEvent $event
     * @return mixed
     */
    public function event(RacesEvent $event, $teamSize);

    /**
     * Get results for a championship
     * @param RacesChampionship $championship
     * @return mixed
     */
    public function championship(RacesChampionship $championship, $teamSize);

    /**
     * Get the list of possible options for scoring this championship
     * @return mixed
     */
    public function getOptions();
}