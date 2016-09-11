<?php

namespace App\Interfaces\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;

interface StandingsInterface
{
    /**
     * Get results summary for an event
     * @param AcEvent $event
     * @return mixed
     */
    public function eventSummary(AcEvent $event);

    /**
     * Get results for an event
     * @param AcEvent $event
     * @return mixed
     */
    public function event(AcEvent $event);

    /**
     * Get results for a championship
     * @param AcChampionship $championship
     * @return mixed
     */
    public function championship(AcChampionship $championship);

    /**
     * Get the list of possible options for scoring this championship
     * @return mixed
     */
    public function getOptions();

}