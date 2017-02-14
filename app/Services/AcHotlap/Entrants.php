<?php

namespace App\Services\AcHotlap;

use App\Models\AcHotlap\AcHotlapSession;
use App\Models\AcHotlap\AcHotlapSessionEntrant;
use App\Models\Driver;
use App\Models\Races\RacesCar;

class Entrants
{
    public function add(AcHotlapSession $session, Driver $driver, RacesCar $car, $time, $sectors)
    {
        $entrant = new AcHotlapSessionEntrant([
            'time' => \Times::fromString($time),
            'sectors' => array_map('Times::fromString', preg_split('/(,|\t)/', $sectors)),
        ]);
        $entrant->driver()->associate($driver);
        $entrant->car()->associate($car);
        $session->entrants()->save($entrant);

        $this->resort($session);

        return $entrant;
    }

    /**
     * Delete a result
     *
     * @param AcHotlapSessionEntrant $entrant
     */
    public function delete(AcHotlapSessionEntrant $entrant)
    {
        $session = $entrant->session;
        $entrant->delete();
        $this->resort($session);
    }

    /**
     * Re-sort the entrants and update their positions
     * @param AcHotlapSession $session
     */
    private function resort(AcHotlapSession $session)
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
     * @param AcHotlapSessionEntrant $a
     * @param AcHotlapSessionEntrant $b
     * @return int
     */
    private function sortEntrants($a, $b)
    {
        return $a->time - $b->time;
    }

    public function areEntrantsEqual($a, $b)
    {
        return $a->time == $b->time;
    }
}