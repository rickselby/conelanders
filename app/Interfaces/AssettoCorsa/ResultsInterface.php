<?php

namespace App\Interfaces\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;
use App\Models\Driver;

interface ResultsInterface
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
     * Get results for a race session
     * @param AcSession $session
     * @return mixed
     */
    public function forRace(AcSession $session);

    /**
     * Get fastest lap detail for a race session
     * @param AcSession $session
     * @return mixed
     */
    public function fastestLaps(AcSession $session);

    /**
     * Get a lap chart for a race session
     * @param AcSession $session
     * @return mixed
     */
    public function lapChart(AcSession $session);

    /**
     * Get all results for a driver
     * @param Driver $driver
     * @return mixed
     */
    public function forDriver(Driver $driver);

}