<?php

namespace App\Interfaces\RallyCross;

use App\Models\RallyCross\RxEvent;

interface DriverStandingsInterface extends StandingsInterface
{
    /**
     * Get the heat results summary for the given event
     * @param RxEvent $event
     * @return mixed
     */
    public function heatsSummary(RxEvent $event);
}