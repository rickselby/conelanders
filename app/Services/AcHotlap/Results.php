<?php

namespace App\Services\AcHotlap;


use App\Interfaces\AcHotlap\ResultsInterface;
use App\Models\AcHotlap\AcHotlapSession;

class Results implements ResultsInterface
{
    /**
     * {@inheritdoc}
     */
    public function forRace(AcHotlapSession $session)
    {
        $firstEntrant = null;
        $lastEntrant = null;

        $raceEntrants = $session->entrants->sortBy('position');

        foreach($raceEntrants AS $entrant) {

            if (!$firstEntrant) {
                $firstEntrant = $entrant;
            }

            // Set time behind first
            $entrant->timeBehindFirst = $entrant->time - $firstEntrant->time;

            // Set time behind car in front
            if ($lastEntrant) {
                $entrant->timeBehindAhead = $entrant->time - $lastEntrant->time;
            } else {
                $entrant->timeBehindAhead = null;
            }

            // Update last entrant
            $lastEntrant = $entrant;
        }

        return $raceEntrants;
    }

    /**
     * {@inheritdoc}
     */
    public function getWinner(AcHotlapSession $session)
    {
        $winners = [];
        foreach($session->entrants AS $entrant) {
            if ($entrant->position == 1) {
                $winners[] = $entrant->driver;
            }
        }
        return $winners;
    }
}
