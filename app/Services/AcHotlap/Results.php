<?php

namespace App\Services\AcHotlap;

use App\Interfaces\AcHotlap\ResultsInterface;
use App\Models\AcHotlap\AcHotlapSession;
use Illuminate\Support\Collection;

class Results implements ResultsInterface
{
    /**
     * {@inheritdoc}
     */
    public function forSession(AcHotlapSession $session)
    {
        $session->load('entrants.driver.nation', 'entrants.car');

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
    public function getWinners(AcHotlapSession $session)
    {
        $winners = new Collection();
        foreach($session->entrants AS $entrant) {
            if ($entrant->position == 1) {
                $winners->push($entrant->driver);
            }
        }
        return $winners;
    }

    /**
     * {@inheritdoc}
     */
    public function withWinners()
    {
        $sessions = AcHotlapSession::with(
            'cars',
            'entrants.driver',
            'entrants.car')->get()->sortByDesc('finish');

        foreach($sessions AS $session) {
            $session->winners = $this->getWinners($session);
        }

        return $sessions;
    }

    public function getSectors(AcHotlapSession $session)
    {
        return $session->entrants->map(function ($item) {
            return count($item->sectors);
        })->max();
    }
}
