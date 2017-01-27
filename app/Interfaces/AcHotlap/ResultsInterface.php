<?php

namespace App\Interfaces\AcHotlap;

use App\Models\AcHotlap\AcHotlapSession;

interface ResultsInterface
{
    /**
     * Get results for a race session
     * @param AcHotlapSession $session
     * @return mixed
     */
    public function forRace(AcHotlapSession $session);

    /**
     * Get the winner of an event
     * @param AcHotlapSession $event
     * @return mixed
     */
    public function getWinner(AcHotlapSession $session);
}