<?php

namespace App\Services\RallyCross;

use App\Interfaces\AssettoCorsa\DriverStandingsInterface;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;

abstract class Standings
{
    const SUM = 0;
    const AVERAGE_SESSION = 1;
    const AVERAGE_EVENT = 2;

    /**
     * See if any events need dropping from the total points
     * @param AcChampionship $championship
     * @param $results
     * @return []
     *
    protected function dropEvents(AcChampionship $championship, $results)
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
            foreach($results AS $id => $result) {

                // Do we need to drop any events?
                if (count($result['points']) > $countEvents) {

                    // Get event IDs beyond the number of events to count
                    $eventsToDrop = array_slice($this->getDropEventIDsSorted($result), $countEvents);

                    // Step through the events to drop
                    foreach($eventsToDrop AS $eventID) {
                        // Remove the points
                        $results[$id]['totalPoints'] -= $result['points'][$eventID];
                        // Mark that this event was dropped
                        $results[$id]['dropped'][] = $eventID;
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
     *
    protected function getDropEventIDsSorted($result)
    {
        // Build an array of points and positions
        $list = [];
        foreach($result['points'] AS $event => $points) {
            $list[$event] = [
                'points' => $points,
                'position' => $result['positions'][$event],
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
     *
    public function arePointsEqual($a, $b)
    {
        return ($a['totalPoints'] == $b['totalPoints'])
            && ($a['positions'] == $b['positions'])
            && ($a['points'] == $b['points']);
    }

    /**
     * Sort overall points
     * @param mixed $a
     * @param mixed $b
     * @return int
     *
    protected function pointsSort($a, $b)
    {
        if ($a['totalPoints'] != $b['totalPoints']) {
            return $b['totalPoints'] > $a['totalPoints'] ? 1 : -1;
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

        $points = [
            'a' => array_values($a['points']),
            'b' => array_values($b['points']),
        ];
        rsort($points['a']);
        rsort($points['b']);

        // So, the drivers have the same positions. So, let's see if they
        // Then, best points; all the way down...
        for($i = 0; $i < max(count($points['a']), count($points['b'])); $i++) {
            // Check both have a position set
            if (isset($points['a'][$i]) && isset($points['b'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($points['a'][$i] != $points['b'][$i]) {
                    return $points['b'][$i] - $points['a'][$i];
                }
            } elseif (isset($points['a'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($points['b'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        // There's nothing more we can do to separate these drivers!
        return 0;
    }

    /**
     * Get the list of options for standings
     * @return array
     */
    public function getOptions()
    {
        return [
            self::SUM => 'Sum',
            self::AVERAGE_SESSION => 'Mean Average: Sessions',
            self::AVERAGE_EVENT => 'Mean Average: Events',
        ];
    }

    /**
     * Calculate the average of the pointsList and sort parts of the results
     * @param $results
     * @return mixed
     *
    protected function averagePoints($results)
    {
        foreach($results AS $id => $result) {
            if (count($result['points'])) {
                $results[$id]['totalPoints'] = array_sum($result['points']) / count($result['points']);
            } else {
                $results[$id]['totalPoints'] = 0;
            }
        }
        return $results;
    }

    /**
     * Sum the pointsList and sort parts of the results
     * @param $results
     * @return mixed
     *
    protected function sumPoints($results)
    {
        foreach($results AS $id => $result) {
            $results[$id]['totalPoints'] = array_sum($result['points']);
        }
        return $results;
    }

    /**
     * Sort the results, and add positions based on the sort
     * @param $results
     * @return mixed
     *
    protected function sortAndAddPositions($results, AcEvent $event = null)
    {
        if ($event) {
            $results = $this->filterResultsPositions($results, $event);
        }
        usort($results, [$this, 'pointsSort']);
        return \Positions::addToArray($results, [$this, 'arePointsEqual']);
    }

    /**
     * Remove any entrants (etc) that have no results
     * @param $championship
     * @param $results
     * @return mixed
     *
    protected function removeEmpty(AcChampionship $championship, $results)
    {
        if ($championship->isComplete()) {
            foreach ($results AS $key => $info) {
                if (count($info['positions']) == 0) {
                    unset($results[$key]);
                }
            }
        }

        return $results;
    }

    protected function filterResultsPositions($results, AcEvent $event)
    {
        foreach($results AS $id => $result) {
            $results[$id]['filteredPositions'] = $this->filterPositions($result['positions'], $event);
        }
        return $results;
    }

    protected function filterPositions($positions, AcEvent $event)
    {
        $sessionIDs = $event->sessions->where('type', AcSession::TYPE_RACE)->pluck('id');
        $filteredPositions = [];
        foreach($positions AS $id => $position) {
            if ($sessionIDs->contains($id)) {
                $filteredPositions[] = $position;
            }
        }
        return $filteredPositions;
    }

     */
}
