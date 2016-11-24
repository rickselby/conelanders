<?php

namespace App\Services\RallyCross;

use App\Interfaces\RallyCross\DriverStandingsInterface;
use App\Models\Driver;
use App\Models\RallyCross\RxChampionship;
use App\Models\RallyCross\RxChampionshipEntrant;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxEventEntrant;

class DriverStandings extends Standings implements DriverStandingsInterface
{
    public function heatsSummary(RxEvent $event)
    {
        $results = [];

        foreach($event->heats AS $session) {
            if ($session->show && \RXSession::hasPoints($session, true)) {
                foreach ($session->entrants AS $entrant) {
                    $entrantID = $entrant->eventEntrant->id;

                    if (!isset($results[$entrantID])) {
                        $results[$entrantID] = $this->initEntrant($entrant->eventEntrant);
                    }

                    if (\RXSession::canBeShown($session)) {
                        $results[$entrantID]['points'][$session->id] = $entrant->points;
                        $results[$entrantID]['positions'][$session->id] = $entrant->position;
                    }
                }
            }
        }

        foreach($event->heatResult AS $heatResult) {
            $results[$heatResult->entrant->id]['champPoints'] = $heatResult->points;
        }

        return $this->sortAndAddPositions($this->sumPoints($results), $event);
    }

    /**
     * {@inheritdoc}
     */
    public function eventSummary(RxEvent $event)
    {
        $results = [];

        foreach(\RXDriverStandings::heatsSummary($event) AS $heatSummary) {
            $entrantID = $heatSummary['entrant']->id;

            if (!isset($results[$entrantID])) {
                $results[$entrantID] = $this->initEntrant($heatSummary['entrant']);
            }

            if (\RXEvent::areHeatsComplete($event) && \RXEvent::canBeShown($event)) {
                $results[$entrantID]['points']['heats'] = $heatSummary['champPoints'];
                $results[$entrantID]['positions']['heats'] = $heatSummary['position'];
            }
        }

        foreach($event->notHeats AS $session) {
            if ($session->show && \RXSession::hasPoints($session)) {
                foreach ($session->entrants AS $entrant) {
                    $entrantID = $entrant->eventEntrant->id;

                    if (!isset($results[$entrantID])) {
                        $results[$entrantID] = $this->initEntrant($entrant->eventEntrant);
                    }

                    if (\RXEvent::canBeShown($event)) {
                        $results[$entrantID]['points'][$session->id] = $entrant->points;
                        $results[$entrantID]['positions'][$session->id] = $entrant->position;
                    }
                }
            }
        }

        return $this->sortAndAddPositions($this->sumPoints($results), $event);
    }

    /**
     * Initialise a results entry for the championship entrant
     * @param RxEventEntrant $entrant
     * @return array
     */
    protected function initEntrant(RxEventEntrant $entrant)
    {
        return [
            'entrant' => $entrant,
            'points' => [],
            'positions' => [],
            'totalPoints' => 0,
            'champPoints' => '',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function event(RxEvent $event)
    {
        $results = [];

        if (\RXEvent::canBeShown($event)) {

            foreach(\RXDriverStandings::heatsSummary($event) AS $heatSummary) {
                $entrantID = $heatSummary['entrant']->id;

                if (!isset($results[$entrantID])) {
                    $results[$entrantID] = $this->initEntrant($heatSummary['entrant']);
                }

                if (\RXEvent::areHeatsComplete($event)) {
                    $results[$entrantID]['points']['heats'] = $heatSummary['champPoints'];
                    $results[$entrantID]['positions']['heats'] = $heatSummary['position'];
                }
            }

            foreach($event->notHeats AS $session) {
                if (\RXSession::hasPoints($session)) {
                    foreach ($session->entrants AS $entrant) {
                        $entrantID = $entrant->eventEntrant->id;

                        if (!isset($results[$entrantID])) {
                            $results[$entrantID] = $this->initEntrant($entrant->eventEntrant);
                        }

                        $results[$entrantID]['points'][$session->id] = $entrant->points + $entrant->fastest_lap_points;
                        $results[$entrantID]['positions'][$session->id] = $entrant->position;
                    }
                }
            }
        }

        return $this->sortAndAddPositions($this->sumPoints($results), $event);
    }

    /**
     * {@inheritdoc}
     */
    public function championship(RxChampionship $championship)
    {
        $results = [];

        foreach($championship->events AS $event) {
            foreach($event->entrants AS $entrant) {
                $results[$entrant->driver->id] = [
                    'entrant' => $entrant->driver,
                    'points' => [],
                    'positions' => [],
                    'positionsWithEquals' => [],
                    'dropped' => [],
                    'totalPoints' => 0,
                ];
            }
        }

        foreach($championship->events AS $event) {
            $eventResults = \RXDriverStandings::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $results[$result['entrant']->driver->id]['points'][$event->id] = $result['totalPoints'];
                $results[$result['entrant']->driver->id]['positions'][$event->id] = $result['position'];
                $results[$result['entrant']->driver->id]['positionsWithEquals'][$event->id] = $eventResultsWithEquals[$key]['position'];
            }
        }

        $results = $this->sumPoints($results);

        // When the championship is complete, hide any drivers that have no results
        $results = $this->removeEmpty($championship, $results);

        // Drop events, if required
        $results = $this->dropEvents($championship, $results);

        return $this->sortAndAddPositions($results);
    }

    /**
     * Extend the points sorting method. If two drivers have equal points, split them by
     * their driver number. The arePointsEqual function will still return true for the two
     * drivers, so they will get the same position. But it looks prettier.
     *
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    public function pointsSort($a, $b)
    {
        $val = parent::pointsSort($a, $b);
        if ($val == 0)
        {
            return strcasecmp($a['entrant']->name, $b['entrant']->name);
        } else {
            return $val;
        }
    }
}
