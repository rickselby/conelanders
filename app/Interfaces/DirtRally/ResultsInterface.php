<?php

namespace App\Interfaces\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;
use App\Models\DirtRally\DirtStage;
use App\Models\Driver;

interface ResultsInterface
{
    /**
     * Get results for an event
     * @param DirtEvent $event
     * @return mixed
     */
    public function getEventResults(DirtEvent $event);

    /**
     * Get results for a single stage
     * @param DirtStage $stage
     * @return mixed
     */
    public function getStageResults(DirtStage $stage);

    /**
     * Get results for the given driver
     * @param Driver $driver
     * @return mixed
     */
    public function forDriver(Driver $driver);

    /**
     * Get the winner of the given season
     * @param DirtSeason $season
     * @return mixed
     */
    public function getSeasonWinner(DirtSeason $season);

    /**
     * Get the winner of the given event
     * @param DirtEvent $event
     * @return mixed
     */
    public function getEventWinner(DirtEvent $event);
    
}