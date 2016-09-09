<?php

namespace App\Interfaces\AssettoCorsa;

use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;
use App\Models\Driver;

interface ResultsInterface
{
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

    /**
     * Get the winner of an event
     * @param AcEvent $event
     * @return mixed
     */
    public function getWinner(AcEvent $event);
}