<?php

namespace App\Services\Races;

use App\Models\Races\RacesSession;
use App\Models\Races\RacesSessionEntrant;
use App\Models\PointsSequence;

class Session
{
    /**
     * Can we show the given session's results to the user?
     *
     * @param RacesSession $session
     *
     * @return bool
     */
    public function canBeShown(RacesSession $session)
    {
        return $session->canBeReleased()
            || \RacesEvent::currentUserInEvent($session->event);
    }
    
    /**
     * Get the directory in which results files are stored
     * 
     * @return string
     */
    public function getResultsFileDirectory()
    {
        return storage_path('uploads/ac-results/');
    }

    /**
     * Get the file name of the results file for the given session
     *
     * @param RacesSession $session
     * @return string
     */
    public function getResultsFileName(RacesSession $session)
    {
        return $session->id.'.json';
    }

    /**
     * Get the whole path to the results file for the given session
     *
     * @param RacesSession $session
     * @return string
     */
    public function getResultsFilePath(RacesSession $session)
    {
        return $this->getResultsFileDirectory().$this->getResultsFileName($session);
    }

    /**
     * Check if the given session has a results file uploaded
     *
     * @param RacesSession $session
     * @return bool
     */
    public function hasResultsFile(RacesSession $session)
    {
        return is_file($this->getResultsFilePath($session));
    }

    /**
     * Check if the given session has results
     *
     * @param RacesSession $session
     * @return bool
     */
    public function hasResults(RacesSession $session)
    {
        if (count($session->entrants)) {
            foreach ($session->entrants AS $entrant) {
                if (!$entrant->position) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if the given session has points entered
     *
     * @param RacesSession $session
     * @return bool
     */
    public function hasPoints(RacesSession $session)
    {
        return $this->checkEntrantsForValue($session, 'points');
    }

    /**
     * Check if the given session has points entered
     *
     * @param RacesSession $session
     * @return bool
     */
    public function hasFastestLapPoints(RacesSession $session)
    {
        return $this->checkEntrantsForValue($session, 'fastest_lap_points');
    }

    /**
     * Check if anyone in this session had ballast
     *
     * @param RacesSession $session
     * @return bool
     */
    public function hasBallast(RacesSession $session)
    {
        return $this->checkEntrantsForValue($session, 'ballast');
    }

    /**
     * Check the entrants for a given key
     *
     * @param RacesSession $session
     * @param string $key
     * @return bool
     */
    private function checkEntrantsForValue(RacesSession $session, $key)
    {
        foreach($session->entrants AS $entrant) {
            if ($entrant->{$key}) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the given session has starting positions
     *
     * @param RacesSession $session
     * @return bool
     */
    public function hasStartingPositions(RacesSession $session)
    {
        foreach($session->entrants AS $entrant) {
            if (!$entrant->started) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set points for entrants to the given points
     *
     * @param RacesSession $session
     * @param $points Keyed array, entrantID => points
     */
    public function setPoints(RacesSession $session, $points)
    {
        foreach($session->entrants AS $entrant) {
            $this->setPointsFor($entrant, $points[$entrant->id]);
        }
    }

    /**
     * Set fastest lap points for entrants to the given points
     *
     * @param RacesSession $session
     * @param $points Keyed array, entrantID => points
     */
    public function setFastestLapPoints(RacesSession $session, $points)
    {
        foreach($session->entrants AS $entrant) {
            // Don't check for DNF / DSQ for flap points?
            $entrant->fastest_lap_points = $points[$entrant->id];
            $entrant->save();
        }
    }

    /**
     * Apply the given points sequence to the session results
     *
     * @param RacesSession $session
     * @param PointsSequence $sequence
     */
    public function applyPointsSequence(RacesSession $session, PointsSequence $sequence)
    {
        $points = \PointSequences::get($sequence);
        foreach($session->entrants AS $entrant) {
            $this->setPointsFor($entrant, isset($points[$entrant->position]) ? $points[$entrant->position] : 0);
        }
    }

    /**
     * Apply the given points sequence to the fastest lap session results
     *
     * @param RacesSession $session
     * @param PointsSequence $sequence
     */
    public function applyFastestLapPointsSequence(RacesSession $session, PointsSequence $sequence)
    {
        $points = \PointSequences::get($sequence);
        foreach($session->entrants AS $entrant) {
            // Don't check for DNF / DSQ for flap points?
            $entrant->fastest_lap_points = isset($points[$entrant->fastest_lap_position]) ? $points[$entrant->fastest_lap_position] : 0;
            $entrant->save();
        }
    }

    /**
     * Set points for an entrant, if they can have points...
     *
     * @param RacesSessionEntrant $entrant
     * @param int|NULL $points
     */
    private function setPointsFor(RacesSessionEntrant $entrant, $points)
    {
        if ($points !== NULL) {
            $entrant->points = $points;
        } else {
            $entrant->points = 0;
        }
        $entrant->save();
    }

    /**
     * Set starting positions for thisSession from results from fromSession
     *
     * @param RacesSession $thisSession
     * @param RacesSession $fromSession
     */
    public function setStartedFromSession(RacesSession $thisSession, RacesSession $fromSession)
    {
        // Get positions
        $positions = [];
        foreach($fromSession->entrants AS $entrant) {
            $positions[$entrant->championshipEntrant->id] = $entrant->position;
        }
        // and set positions
        foreach($thisSession->entrants AS $entrant) {
            $entrant->started = $positions[$entrant->championshipEntrant->id];
            $entrant->save();
        }
    }

    /**
     * Set starting positions for the session
     * 
     * @param RacesSession $session
     * @param $positions Keyed array, entrantID => position
     */
    public function setStarted(RacesSession $session, $positions)
    {
        foreach($session->entrants AS $entrant) {
            $entrant->started = $positions[$entrant->id];
            $entrant->save();
        }
    }
}
