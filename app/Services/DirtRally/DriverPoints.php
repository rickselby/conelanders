<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtPointsSystem;
use App\Models\DirtRally\DirtSeason;
use Illuminate\Database\Eloquent\Collection;

class DriverPoints
{
    public function forEvent(DirtPointsSystem $system, DirtEvent $event)
    {
        $points = [];

        if ($event->isComplete()) {
            $system = \DirtRallyPointSequences::forSystem($system);
            /**
             * Get the results for this event, and mangle them into points
             */
            foreach (\DirtRallyResults::getEventResults($event) AS $position => $result) {
                $points[$result['driver']->id] = [
                    'entity' => $result['driver'],
                    'stageTimesByOrder' => $result['stage'],
                    'dnf' => $result['dnf'],
                    'total' => [
                        'time' => $result['total'],
                        'points' => 0
                    ],
                    'stagePoints' => [],
                    'eventPosition' => $position,
                    'eventPoints' => (isset($system['event'][$position]) && !$result['dnf'] && $result['total'])
                        ? $system['event'][$position]
                        : 0,
                ];
            }

            // Get points for each result for each stage
            foreach ($event->stages AS $stage) {
                foreach ($stage->results AS $result) {
                    $points[$result->driver->id]['stagePoints'][$stage->id] =
                        isset($system['stage'][$result->position]) && !$result->dnf
                            ? $system['stage'][$result->position]
                            : 0;
                }
                foreach($points AS $driverID => $point) {
                    // Map the stage times by ID, not order
                    $points[$driverID]['stageTimes'][$stage->id] =
                        $point['stageTimesByOrder'][$stage->order];
                }
            }

            // Sum event points and stage points to get total points
            foreach ($points AS $driverID => $point) {
                $points[$driverID]['total']['points'] = $point['eventPoints'];
                foreach ($point['stagePoints'] AS $stagePoint) {
                    $points[$driverID]['total']['points'] += $stagePoint;
                }
            }

            // Sort by points and position
            usort($points, function ($a, $b) {
                if ($a['total']['points'] != $b['total']['points']) {
                    return $b['total']['points'] - $a['total']['points'];
                } else {
                    return $a['eventPosition'] - $b['eventPosition'];
                }
            });
        }

        return $points;
    }

    /**
     * Get points for the given system for the given season
     * @param DirtPointsSystem $system
     * @param DirtSeason $season
     * @return array
     */
    public function forSeason(DirtPointsSystem $system, DirtSeason $season)
    {
        $points = [];
        foreach($season->events AS $event) {
            if ($event->isComplete()) {
                foreach ($this->forEvent($system, $event) AS $position => $result) {
                    $points[$result['entity']->id]['entity'] = $result['entity'];
                    $points[$result['entity']->id]['points'][$event->id] = $result['total']['points'];
                    $points[$result['entity']->id]['positions'][] = $position;
                }
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Get points for the given system for each event in the given championship
     * @param DirtPointsSystem $system
     * @param Collection $seasons
     * @return array
     */
    public function overview(DirtPointsSystem $system, Collection $seasons)
    {
        $points = [];
        foreach($seasons AS $season) {
            foreach ($season->events AS $event) {
                if ($event->isComplete()) {
                    foreach ($this->forEvent($system, $event) AS $position => $result) {
                        foreach($result['stagePoints'] AS $stage => $stagePoints) {
                            $points[$result['entity']->id]['stages'][$stage] = $stagePoints;
                        }
                        $points[$result['entity']->id]['events'][$event->id] = $result['eventPoints'];
                        $points[$result['entity']->id]['entity'] = $result['entity'];
                        $points[$result['entity']->id]['points'][$event->id] = $result['total']['points'];
                        $points[$result['entity']->id]['positions'][] = $position;
                    }
                }
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Get overall points for the given system (on the given collection of seasons)
     * @param DirtPointsSystem $system
     * @param Collection $seasons
     * @return array
     */
    public function overall(DirtPointsSystem $system, Collection $seasons)
    {
        $points = [];
        // Step through the seasons and pull in results
        foreach($seasons AS $season) {
            foreach ($this->forSeason($system, $season) AS $position => $result) {
                $points[$result['entity']->id]['entity'] = $result['entity'];
                $points[$result['entity']->id]['points'][$season->id] = $result['total'];
                $points[$result['entity']->id]['positions'][] = $position;
                $points[$result['entity']->id]['seasonPosition'][$season->id] = $result['position'];
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Take a list of points, sum them, and sort them...
     * @param array $points
     * @return array
     */
    protected function sumAndSort($points)
    {
        // Step through each driver, sum their points, and sort their positions
        foreach($points AS $driverID => $point) {
            $points[$driverID]['total'] = array_sum($point['points']);
            sort($points[$driverID]['positions']);
        }

        // Sort the drivers
        usort($points, [$this, 'pointsSort']);

        // Step through the drivers and set their positions.
        // If a driver is equal to the one above, set as equal.
        foreach($points AS $pos => $point) {
            $points[$pos]['position'] = $pos + 1;

            // If the next value is the same as this one, append an equals
            if (isset($points[$pos+1]) && $this->arePointsEqual($point, $points[$pos+1])) {
                $points[$pos]['position'] .= '=';
            } elseif($pos > 0 && $this->arePointsEqual($point, $points[$pos-1])) {
                // If the previous value is the same as this one, use the same position string
                $points[$pos]['position'] = $points[$pos-1]['position'];
            }
        }

        return $points;
    }

    /**
     * Sort overall points
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    protected function pointsSort($a, $b)
    {
        // First, total points
        if (!is_array($a['total'])) {
            if ($a['total'] != $b['total']) {
                return $b['total'] > $a['total'] ? 1 : -1;
            }
        } else {
            if ($a['total']['points'] != $b['total']['points']) {
                return $b['total']['points'] > $a['total']['points'] ? 1 : -1;
            }
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

        // There's nothing more we can do to separate these drivers!
        return 0;
    }

    /**
     * Check if points are equal
     * @param array $a
     * @param array $b
     * @return bool
     */
    protected function arePointsEqual($a, $b)
    {
        return $a['total'] == $b['total']
            && $a['positions'] == $b['positions']
            && count($a['points']) == count($b['points']);
    }

}
