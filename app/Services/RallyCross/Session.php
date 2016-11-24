<?php

namespace App\Services\RallyCross;

use App\Models\Driver;
use App\Models\PointsSequence;
use App\Models\RallyCross\RxCar;
use App\Models\RallyCross\RxSession;
use App\Models\RallyCross\RxSessionEntrant;

class Session
{
    /**
     * Can we show the given session's results to the user?
     *
     * @param RxSession $session
     *
     * @return bool
     */
    public function canBeShown(RxSession $session)
    {
        return $session->canBeReleased()
        || \RXEvent::currentUserInEvent($session->event);
    }

    /**
     * Check if the entrants for the given session have points
     * @param RxSession $session
     * @return bool
     */
    public function hasPoints(RxSession $session)
    {
        return $this->checkEntrantsForValue($session, 'points');
    }

    public function hasPenalties(RxSession $session)
    {
        return $this->checkEntrantsForValue($session, 'penalty');
    }

    public function hasRaces(RxSession $session)
    {
        return $this->checkEntrantsForValue($session, 'race');
    }

    /**
     * Apply the given points sequence to the session results
     *
     * @param RxSession $session
     * @param PointsSequence $sequence
     */
    public function applyPointsSequence(RxSession $session, PointsSequence $sequence)
    {
        $points = \PointSequences::get($sequence);
        foreach($session->entrants AS $entrant) {
            $this->setPointsFor($entrant, isset($points[$entrant->position]) ? $points[$entrant->position] : 0);
        }
    }

    /**
     * Set points for entrants to the given points
     *
     * @param RxSession $session
     * @param [] $points Keyed array, entrantID => points
     */
    public function setPoints(RxSession $session, $points)
    {
        foreach($session->entrants AS $entrant) {
            $this->setPointsFor($entrant, $points[$entrant->id]);
        }
    }

    /**
     * Set points for an entrant, if they can have points...
     *
     * @param RxSessionEntrant $entrant
     * @param int|NULL $points
     */
    private function setPointsFor(RxSessionEntrant $entrant, $points)
    {
        if ($points !== NULL) {
            $entrant->points = $points;
        } else {
            $entrant->points = 0;
        }
        $entrant->save();
    }

    /**
     * Check the entrants for a given key
     *
     * @param RxSession $session
     * @param string $key
     * @return bool
     */
    private function checkEntrantsForValue(RxSession $session, $key)
    {
        foreach($session->entrants AS $entrant) {
            if ($entrant->{$key}) {
                return true;
            }
        }

        return false;
    }
}