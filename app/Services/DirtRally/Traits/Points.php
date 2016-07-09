<?php

namespace App\Services\DirtRally\Traits;

use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtSeason;
use Illuminate\Database\Eloquent\Collection;

trait Points
{
    /**
     * {@inheritdoc}
     */
    public function forSeason(DirtSeason $season)
    {
        $points = [];
        foreach($season->events AS $event) {
            if ($event->isComplete()) {
                // This should call a facade, but could be drivers or nations points...
                foreach ($this->forEvent($event) AS $result) {
                    $points[$result['entity']->id]['entity'] = $result['entity'];
                    $points[$result['entity']->id]['points'][$event->id] = $result['total']['points'];
                    $points[$result['entity']->id]['positions'][$event->id] = $result['position'];
                }
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * {@inheritdoc}
     */
    public function overall(DirtChampionship $championship)
    {
        $points = [];
        $championship->load([
            'seasons.events.stages.results.driver.nation',
            'seasons.events.positions.driver.nation',
            'seasons.events.season.championship.eventPointsSequence',
            'seasons.events.season.championship.stagePointsSequence',
        ]);
        // Step through the seasons and pull in results
        foreach($championship->seasons AS $season) {
            // This should call a facade, but could be drivers or nations points...
            foreach ($this->forSeason($season) AS $result) {
                $points[$result['entity']->id]['entity'] = $result['entity'];
                $points[$result['entity']->id]['points'][$season->id] = $result['total'];
                $points[$result['entity']->id]['positions'][$season->id] = $result['position'];
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
            $points[$driverID]['sortedPositions'] = $points[$driverID]['positions'];
            sort($points[$driverID]['sortedPositions']);
        }

        // Sort the drivers
        usort($points, [$this, 'pointsSort']);

        $points = \Positions::addToArray($points, [$this, 'arePointsEqual']);

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
        for($i = 0; $i < max(count($a['sortedPositions']), count($b['sortedPositions'])); $i++) {
            // Check both have a position set
            if (isset($a['sortedPositions'][$i]) && isset($b['sortedPositions'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($a['sortedPositions'][$i] != $b['sortedPositions'][$i]) {
                    return $a['sortedPositions'][$i] - $b['sortedPositions'][$i];
                }
            } elseif (isset($a['sortedPositions'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($b['sortedPositions'][$i])) {
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
    public function arePointsEqual($a, $b)
    {
        return $a['total'] == $b['total']
        && $a['positions'] == $b['positions']
        && count($a['points']) == count($b['points']);
    }

}
