<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\DriverStandingsInterface;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;

class DriverStandings implements DriverStandingsInterface
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
        $championship->load('events.sessions.entrants.championshipEntrant.driver.nation');
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

    /**
     * See if any events need dropping from the total points
     * @param AcChampionship $championship
     * @param $results
     * @return []
     */
    private function dropEvents(AcChampionship $championship, $results)
    {
        $dropEvents = $shownEvents = 0;

        // First, calculate how many dropped events to show
        if ($championship->drop_events != 0) {

            // we need to know how many events are available
            $totalEvents = $shownEvents = 0;
            foreach($championship->events AS $event) {
                $totalEvents++;
                if (\ACEvent::canBeShown($event)) {
                    $shownEvents++;
                }
            }

            // then work out how many dropped events should be shown
            // for 1, show it half way through
            // for 2, show one after a third, and the 2nd after 2/3rds
            // etc
            for ($i = 1; $i <= $championship->drop_events; $i++) {
                if ($shownEvents >= (($i / ($championship->drop_events + 1)) * $totalEvents)) {
                    $dropEvents++;
                }
            }
        }

        if ($dropEvents != 0) {
            // how many events can count?
            $countEvents = $shownEvents - $dropEvents;

            // Work through each entrant
            foreach($results AS $entrantID => $result) {

                // Do we need to drop any events?
                if (count($result['eventPoints']) > $countEvents) {

                    // Get event IDs beyond the number of events to count
                    $eventsToDrop = array_slice($this->getDropEventIDsSorted($result), $countEvents);

                    // Step through the events to drop
                    foreach($eventsToDrop AS $eventID) {
                        // Remove the points
                        $results[$entrantID]['points'] -= $result['eventPoints'][$eventID];
                        // Mark that this event was dropped
                        $results[$entrantID]['dropped'][] = $eventID;
                    }
                }

            }
        }
        return $results;
    }

    /**
     * Sort championship results by points (descending) and position (ascending) and return the event IDs in that order
     * @param [] $result
     * @return int[]
     */
    private function getDropEventIDsSorted($result)
    {
        // Build an array of points and positions
        $list = [];
        foreach($result['eventPoints'] AS $event => $points) {
            $list[$event] = [
                'points' => $points,
                'position' => $result['eventPositions'][$event],
            ];
        }
        // Sort it
        uasort($list, function($a, $b) {
            if ($a['points'] == $b['points']) {
                // Points are the same; drop the higher numbered position
                return $a['position'] - $b['position'];
            } else {
                return $b['points'] - $a['points'];
            }
        });
        return array_keys($list);
    }

    /**
     * Check two event results to see if they are equal
     * @param $a
     * @param $b
     * @return bool
     */
    public function arePointsEqual($a, $b)
    {
        return ($a['points'] == $b['points'])
        && ($a['positions'] == $b['positions'])
        && ($a['pointsList'] == $b['pointsList']);
    }

    /**
     * Sort overall points
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    private function pointsSort($a, $b)
    {
        if ($a['points'] != $b['points']) {
            return $b['points'] > $a['points'] ? 1 : -1;
        }

        // Then, best finishing positions; all the way down...
        for($i = 0; $i < max(count($a['positions']), count($b['positions'])); $i++) {
            // Check both have a position set
            if (isset($a['positions'][$i]) && isset($b['positions'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($a['positions'][$i] != $b['positions'][$i]) {
                    return $a['positions'][$i] - $b['positions'][$i];
                }
            } elseif (isset($a['positions'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($b['positions'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        // So, the drivers have the same positions. So, let's see if they
        // Then, best points; all the way down...
        for($i = 0; $i < max(count($a['pointsList']), count($b['pointsList'])); $i++) {
            // Check both have a position set
            if (isset($a['pointsList'][$i]) && isset($b['pointsList'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($a['pointsList'][$i] != $b['pointsList'][$i]) {
                    return $b['pointsList'][$i] - $a['pointsList'][$i];
                }
            } elseif (isset($a['pointsList'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($b['pointsList'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        // There's nothing more we can do to separate these drivers!
        return 0;
    }

}
