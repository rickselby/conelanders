<?php

namespace App\Interfaces\RallyCross;

use App\Models\Driver;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;

interface ResultsInterface
{
    /**
     * Get results for a race session
     * @param RxSession $session
     * @return mixed
     */
    public function forRace(RxSession $session);

    /**
     * Get fastest lap detail for a race session
     * @param RxSession $session
     * @return mixed
     */
    public function fastestLaps(RxSession $session);

    /**
     * Get all results for a driver
     * @param Driver $driver
     * @return mixed
     */
    public function forDriver(Driver $driver);

    /**
     * Get the winner of an event
     * @param RxEvent $event
     * @return mixed
     */
    public function getWinner(RxEvent $event);
}