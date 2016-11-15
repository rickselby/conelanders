<?php

namespace App\Services\RallyCross;

use App\Models\PointsSequence;
use App\Models\RallyCross\RxEvent;

class Event
{
    /**
     * Check if we have heat results yet
     *
     * @param RxEvent $event
     * @return int
     */
    public function hasHeatResults(RxEvent $event)
    {
        return $event->heatResult->count();
    }

    /**
     * Check if all heats are marked as complete
     * @param RxEvent $event
     * @return bool
     */
    public function areHeatsComplete(RxEvent $event)
    {
        if (!$event->heats->count()) {
            return false;
        }
        foreach($event->heats AS $session) {
            if (!$session->show) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get heat results for the current event
     * @param RxEvent $event
     * @return mixed
     */
    public function getHeatResults(RxEvent $event)
    {
        $results = [];
        foreach($event->heats AS $session) {
            foreach($session->entrants AS $entrant) {
                if (!isset($results[$entrant->eventEntrant->id])) {
                    $results[$entrant->eventEntrant->id] = [
                        'entrant' => $entrant->eventEntrant,
                        'heatPoints' => 0,
                        'positions' => [],
                        'points' => 0,
                    ];
                }
                $results[$entrant->eventEntrant->id]['positions'][] = $entrant->position;
                $results[$entrant->eventEntrant->id]['heatPoints'] += $entrant->points;
            }
        }

        if ($this->hasHeatResults($event)) {
            foreach($event->heatResult AS $heatResult) {
                $results[$heatResult->entrant->id]['points'] = $heatResult->points;
            }
        }

        usort($results, [$this, 'sortHeatResults']);

        return \Positions::addToArray($results, [$this, 'areHeatResultsEqual']);
    }

    /**
     * Sort heat results
     *
     * @param $a
     * @param $b
     * @return int
     */
    private function sortHeatResults($a, $b)
    {
        if ($a['heatPoints'] != $b['heatPoints']) {
            return $b['heatPoints'] - $a['heatPoints'];
        }

        $positions = [
            'a' => array_values($a['positions']),
            'b' => array_values($b['positions']),
        ];
        sort($positions['a']);
        sort($positions['b']);

        // Then, best finishing positions; all the way down...
        for($i = 0; $i < max(count($positions['a']), count($positions['b'])); $i++) {
            // Check both have a position set
            if (isset($positions['a'][$i]) && isset($positions['b'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($positions['a'][$i] != $positions['b'][$i]) {
                    return $positions['a'][$i] - $positions['b'][$i];
                }
            } elseif (isset($positions['a'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($positions['b'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        return 0;
    }

    /**
     * Check if two heat results are identical
     * @param $a
     * @param $b
     * @return bool
     */
    public function areHeatResultsEqual($a, $b)
    {
        return ($a['heatPoints'] == $b['heatPoints'])
            && ($a['positions'] == $b['positions']);
    }

    /**
     * Apply the given points sequence to the session results
     *
     * @param RxEvent $event
     * @param PointsSequence $sequence
     */
    public function applyHeatsPointsSequence(RxEvent $event, PointsSequence $sequence)
    {
        $points = \PointSequences::get($sequence);
        foreach($this->getHeatResults($event) AS $entrant) {
            $this->setHeatsPointsFor($event, $entrant, isset($points[$entrant['position']]) ? $points[$entrant['position']] : 0);
        }
    }

    /**
     * Set points for entrants to the given points
     *
     * @param RxEvent $event
     * @param [] $points Keyed array, entrantID => points
     */
    public function setHeatsPoints(RxEvent $event, $points)
    {
        foreach($this->getHeatResults($event) AS $entrant) {
            $this->setHeatsPointsFor($event, $entrant, $points[$entrant['driver']->id]);
        }
    }

    /**
     * Set points for an entrant, if they can have points...
     *
     * @param RxEvent $event
     * @param [] $entrant
     * @param int|NULL $points
     */
    private function setHeatsPointsFor(RxEvent $event, $entrant, $points)
    {
        $heatResult = $event->heatResult()->firstOrNew([
            'rx_event_entrant_id' => $entrant['entrant']->id,
        ]);
        $heatResult->fill([
            'position' => $entrant['position'],
            'points' => $points !== NULL ? $points : 0,
        ])->save();
    }


}