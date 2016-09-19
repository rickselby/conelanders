<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\DriverStandingsInterface;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcChampionshipEntrant;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;

class DriverStandings extends Standings implements DriverStandingsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(AcEvent $event)
    {
        $results = [];

        foreach($event->sessions AS $session) {
            if (\ACSession::hasPoints($session)) {
                foreach ($session->entrants AS $entrant) {
                    $entrantID = $entrant->championshipEntrant->id;

                    if (!isset($results[$entrantID])) {
                        $results[$entrantID] = $this->initEntrant($entrant->championshipEntrant);
                    }

                    if (\ACSession::canBeShown($session)) {
                        $results[$entrantID]['points'][$session->id] = $entrant->points + $entrant->fastest_lap_points;
                        $results[$entrantID]['positions'][$session->id] = $entrant->position;
                    }
                }
            }
        }

        return $this->sortAndAddPositions($this->sumPoints($results), $event);
    }

    /**
     * Initialise a results entry for the championship entrant
     * @param AcChampionshipEntrant $entrant
     * @return array
     */
    protected function initEntrant(AcChampionshipEntrant $entrant)
    {
        return [
            'entrant' => $entrant,
            'points' => [],
            'positions' => [],
            'totalPoints' => 0,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function event(AcEvent $event)
    {
        $results = [];

        if (\ACEvent::canBeShown($event)) {
            foreach($event->sessions AS $session) {
                if (\ACSession::hasPoints($session)) {
                    foreach ($session->entrants AS $entrant) {
                        $entrantID = $entrant->championshipEntrant->id;

                        if (!isset($results[$entrantID])) {
                            $results[$entrantID] = $this->initEntrant($entrant->championshipEntrant);
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
    public function championship(AcChampionship $championship)
    {
        $results = [];

        foreach($championship->entrants AS $entrant) {
            $results[$entrant->id] = [
                'entrant' => $entrant,
                'points' => [],
                'positions' => [],
                'positionsWithEquals' => [],
                'dropped' => [],
                'totalPoints' => 0,
            ];
        }

        foreach($championship->events AS $event) {
            $eventResults = \ACDriverStandings::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $results[$result['entrant']->id]['points'][$event->id] = $result['totalPoints'];
                $results[$result['entrant']->id]['positions'][$event->id] = $result['position'];
                $results[$result['entrant']->id]['positionsWithEquals'][$event->id] = $eventResultsWithEquals[$key]['position'];
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
            return $a['entrant']->number - $b['entrant']->number;
        } else {
            return $val;
        }
    }

}
