<?php

namespace App\Interfaces\AcHotlap;

use App\Models\AcHotlap\AcHotlapSession;
use Illuminate\Support\Collection;

interface ResultsInterface
{
    /**
     * Get results for a race session
     * @param AcHotlapSession $session
     * @return mixed
     */
    public function forSession(AcHotlapSession $session);

    /**
     * Get the winner of an event
     * @param AcHotlapSession $event
     * @return mixed
     */
    public function getWinners(AcHotlapSession $session);

    /**
     * Get a full list of sessions with winners
     * @return Collection
     */
    public function withWinners();

    /**
     * Get the sector count for the session
     * @return integer
     */
    public function getSectors(AcHotlapSession $session);
}