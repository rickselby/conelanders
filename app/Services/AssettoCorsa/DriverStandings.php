<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\DriverStandingsInterface;
use App\Models\AssettoCorsa\AcChampionship;
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
            foreach($session->entrants AS $entrant) {
                $entrantID = $entrant->championshipEntrant->id;

                if (!isset($results[$entrantID])) {
                    $results[$entrantID] = [
                        'entrant' => $entrant->championshipEntrant,
                        'points' => 0,
                        'pointsList' => [],
                        'positions' => [],
                    ];
                }

                if (\ACSession::canBeShown($session)) {
                    $results[$entrantID]['sessionPoints'][$session->id] = $entrant->points + $entrant->fastest_lap_points;
                    $results[$entrantID]['points'] += $results[$entrantID]['sessionPoints'][$session->id];
                    $results[$entrantID]['pointsList'][] = $results[$entrantID]['sessionPoints'][$session->id];
                    rsort($results[$entrantID]['pointsList']);
                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$entrantID]['positions'][] = $entrant->position;
                        sort($results[$entrantID]['positions']);
                    }
                    $results[$entrantID]['sessionPositions'][$session->id] = $entrant->position;
                }
            }
        }

        usort($results, [$this, 'pointsSort']);

        $results = \Positions::addToArray($results, [$this, 'arePointsEqual']);

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function event(AcEvent $event)
    {
        $results = [];

        if (\ACEvent::canBeShown($event)) {
            foreach($event->sessions AS $session) {
                foreach($session->entrants AS $entrant) {
                    $entrantID = $entrant->championshipEntrant->id;

                    if (!isset($results[$entrantID])) {
                        $results[$entrantID] = [
                            'entrant' => $entrant->championshipEntrant,
                            'points' => 0,
                            'pointsList' => [],
                            'positions' => [],
                        ];
                    }
                    $results[$entrantID]['points'] += $entrant->points + $entrant->fastest_lap_points;
                    $results[$entrantID]['pointsList'][] = $entrant->points + $entrant->fastest_lap_points;
                    rsort($results[$entrantID]['pointsList']);
                    if ($session->type == AcSession::TYPE_RACE) {
                        $results[$entrantID]['positions'][] = $entrant->position;
                        sort($results[$entrantID]['positions']);
                    }
                }
            }
        }

        usort($results, [$this, 'pointsSort']);

        $results = \Positions::addToArray($results, [$this, 'arePointsEqual']);

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function championship(AcChampionship $championship)
    {
        $results = [];

        foreach($championship->entrants AS $entrant) {
            $results[$entrant->id]['entrant'] = $entrant;
            $results[$entrant->id]['points'] = 0;
            $results[$entrant->id]['pointsList'] = [];
            $results[$entrant->id]['eventPoints'] = [];
            $results[$entrant->id]['positions'] = [];
            $results[$entrant->id]['eventPositions'] = [];
            $results[$entrant->id]['dropped'] = [];
        }
        foreach($championship->events AS $event) {
            $eventResults = \ACDriverStandings::event($event);
            $eventResultsWithEquals = \Positions::addEquals($eventResults);
            foreach ($eventResults AS $key => $result) {
                $entrantID = $result['entrant']->id;
                $results[$entrantID]['points'] += $result['points'];
                $results[$entrantID]['pointsList'][] = $result['points'];
                $results[$entrantID]['eventPoints'][$event->id] = $result['points'];
                $results[$entrantID]['positions'][] = $result['position'];
                $results[$entrantID]['eventPositions'][$event->id] = $eventResultsWithEquals[$key]['position'];
                rsort($results[$entrantID]['pointsList']);
                sort($results[$entrantID]['positions']);
            }
        }

        // When the championship is complete, hide any drivers that have no results
        if ($championship->isComplete()) {
            foreach ($results AS $key => $info) {
                if (count($info['positions']) == 0) {
                    unset($results[$key]);
                }
            }
        }

        $results = $this->dropEvents($championship, $results);
        usort($results, [$this, 'pointsSort']);
        $results = \Positions::addToArray($results, [$this, 'arePointsEqual']);

        return $results;
    }

}
