<?php

namespace App\Interfaces\Races;

use App\Models\Races\RacesEvent;
use App\Models\Races\RacesSession;
use App\Models\Driver;

interface ResultsInterface
{
    /**
     * Get results for a race session
     * @param RacesSession $session
     * @return mixed
     */
    public function forRace(RacesSession $session);

    /**
     * Get fastest lap detail for a race session
     * @param RacesSession $session
     * @return mixed
     */
    public function fastestLaps(RacesSession $session);

    /**
     * Get a lap chart for a race session
     * @param RacesSession $session
     * @return mixed
     */
    public function lapChart(RacesSession $session);

    /**
     * Get all results for a driver
     * @param Driver $driver
     * @return mixed
     */
    public function forDriver(Driver $driver);

    /**
     * Get the winner of an event
     * @param RacesEvent $event
     * @return mixed
     */
    public function getWinner(RacesEvent $event);
}