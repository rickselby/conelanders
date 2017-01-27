<?php

namespace App\Services\Races;

use App\Interfaces\Races\DriverStandingsInterface;
use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesChampionshipEntrant;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesPenalty;
use App\Models\Races\RacesSession;

class DriverStandings extends Standings implements DriverStandingsInterface
{
    /**
     * {@inheritdoc}
     */
    public function eventSummary(RacesEvent $event)
    {
        $results = [];

        foreach($event->sessions AS $session) {
            if (\RacesSession::hasPoints($session)) {
                foreach ($session->entrants AS $entrant) {
                    $entrantID = $entrant->championshipEntrant->id;

                    if (!isset($results[$entrantID])) {
                        $results[$entrantID] = $this->initEntrant($entrant->championshipEntrant);
                    }

                    if (\RacesSession::canBeShown($session) || \Gate::check('edit', $event)) {
                        $results[$entrantID]['points'][$session->id] = $entrant->points + $entrant->fastest_lap_points;
                        $results[$entrantID]['positions'][$session->id] = $entrant->position;
                    }

                    foreach($entrant->penalties AS $penalty) {
                        $results[$entrantID]['points'][$session->id] -= $penalty->points;
                        $results[$entrantID]['penalties'][] = $penalty;
                    }
                }
            }
        }

        return $this->sortAndAddPositions($this->sumPoints($results), $event);
    }

    /**
     * Initialise a results entry for the championship entrant
     * @param RacesChampionshipEntrant $entrant
     * @return array
     */
    protected function initEntrant(RacesChampionshipEntrant $entrant)
    {
        return [
            'entrant' => $entrant,
            'points' => [],
            'positions' => [],
            'totalPoints' => 0,
            'penalties' => [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function event(RacesEvent $event)
    {
        $results = [];

        if (\RacesEvent::canBeShown($event) || \Gate::check('edit', $event)) {
            foreach($event->sessions AS $session) {
                if (\RacesSession::hasPoints($session)) {
                    foreach ($session->entrants AS $entrant) {
                        $entrantID = $entrant->championshipEntrant->id;

                        if (!isset($results[$entrantID])) {
                            $results[$entrantID] = $this->initEntrant($entrant->championshipEntrant);
                        }

                        $results[$entrantID]['points'][$session->id] = $entrant->points + $entrant->fastest_lap_points;
                        $results[$entrantID]['positions'][$session->id] = $entrant->position;

                        foreach($entrant->penalties AS $penalty) {
                            $results[$penalty->entrant->championshipEntrant->id]['points'][$session->id] -= $penalty->points;
                            $results[$penalty->entrant->championshipEntrant->id]['penalties'][] = $penalty;
                        }
                    }
                }
            }
        }

        return $this->sortAndAddPositions($this->sumPoints($results), $event);
    }

    /**
     * {@inheritdoc}
     */
    public function championship(RacesChampionship $championship)
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
                'penalties' => [],
            ];
        }

        foreach($championship->events AS $event) {
            $eventResults = \RacesDriverStandings::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $results[$result['entrant']->id]['points'][$event->id] = $result['totalPoints'];
                $results[$result['entrant']->id]['positions'][$event->id] = $result['position'];
                $results[$result['entrant']->id]['positionsWithEquals'][$event->id] = $eventResultsWithEquals[$key]['position'];
                $results[$result['entrant']->id]['penalties'] = array_merge($results[$result['entrant']->id]['penalties'], $result['penalties']);
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
