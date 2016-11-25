<?php

namespace App\Interfaces\RallyCross;

use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxEvent;

interface StandingsInterface
{
    /**
     * Get results summary for an event
     * @param RxEvent $event
     * @return mixed
     */
    public function eventSummary(RxEvent $event);

    /**
     * Get results for an event
     * @param RxEvent $event
     * @return mixed
     */
    public function event(RxEvent $event);

    /**
     * Get results for a championship
     * @param RxChampionship $championship
     * @return mixed
     */
    public function championship(RxChampionship $championship);

}