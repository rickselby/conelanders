<?php

namespace App\Services\RallyCross;

use App\Models\Driver;
use App\Models\PointsSequence;
use App\Models\RallyCross\RxCar;
use App\Models\RallyCross\RxEventEntrant;
use App\Models\RallyCross\RxSession;
use App\Models\RallyCross\RxSessionEntrant;

class Entrants
{
    /**
     * Add a new result to the session
     *
     * @param RxSession $session
     * @param RxEventEntrant $entrant
     * @param string $race
     * @param int $time
     * @param int $penalty
     * @param bool $dnf
     * @param bool $dsq
     */
    public function add(RxSession $session, RxEventEntrant $entrant, $race, $time, $penalty, $lap, $dnf, $dsq)
    {
        $entry = new RxSessionEntrant([
            'race' => $race,
            'time' => $time,
            'penalty' => $penalty,
            'lap' => $lap,
            'dnf' => $dnf,
            'dsq' => $dsq,
        ]);

        $entry->eventEntrant()->associate($entrant);
        $session->entrants()->save($entry);

        $this->resort($session);
    }

    /**
     * Delete a result
     *
     * @param RxSessionEntrant $entrant
     */
    public function delete(RxSessionEntrant $entrant)
    {
        $session = $entrant->session;
        $entrant->delete();
        $this->resort($session);
    }

    /**
     * Re-sort the entrants and update their positions
     * @param RxSession $session
     */
    private function resort(RxSession $session)
    {
        /**
         * Get a list of the entrants in the correct order,
         * in a keyed array
         */
        $entrants = $session->entrants->sort(function($a, $b) {
            return $this->sortEntrants($a, $b);
        })->all();

        \Positions::addToArray($entrants, [$this, 'areEntrantsEqual']);

        /**
         * Save each model
         */
        foreach($entrants AS $entrant) {
            $entrant->save();
        }
    }

    /**
     * Sort the entrants into their finishing order
     * @param RxSessionEntrant $a
     * @param RxSessionEntrant $b
     * @return int
     */
    private function sortEntrants($a, $b)
    {
        if ($a->dsq && $b->dsq) {
            return 0;
        } elseif ($a->dsq || $b->dsq) {
            return $a->dsq ? 1 : -1;
        } elseif ($a->dnf && $b->dnf) {
            return 0;
        } elseif ($a->dnf || $b->dnf) {
            return $a->dnf ? 1 : -1;
        } else {
            return $a->totalTime - $b->totalTime;
        }
    }

    public function areEntrantsEqual($a, $b)
    {
        return
            !($a->dsq xor $b->dsq)
            &&
            !($a->dnf xor $b->dnf)
            &&
            $a->totalTime == $b->totalTime;
    }
}