<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Point;
use App\Models\PointsSequence;
use App\Models\PointsSystem;
use App\Models\Season;
use Illuminate\Database\Eloquent\Collection;

class Points
{
    public function forSystem(PointsSystem $system)
    {
        $points = ['event' => [], 'stage' => []];
        foreach($system->eventSequence->points AS $point) {
            $points['event'][$point->position] = $point->points;
        }
        foreach($system->stageSequence->points AS $point) {
            $points['stage'][$point->position] = $point->points;
        }
        return $points;
    }

    public function setForSequence(PointsSequence $sequence, $pointsList)
    {
        foreach($pointsList AS $position => $points) {
            // Get the current points, or create...
            /** @var Point $point */
            $point = $sequence->points->where('position', $position)->first();
            if (!$point) {
                if ($points != 0) {
                    $point = new Point(['position' => $position, 'points' => $points]);
                    $sequence->points()->save($point);
                }
            } else {
                if ($points != 0) {
                    $point->points = $points;
                    $point->save();
                } else {
                    $point->delete();
                }
            }
        }
    }

    public function forEvent(PointsSystem $system, Event $event)
    {
        $system = $this->forSystem($system);
        $points = [];

        /**
         * Get the results for this event, and mangle them into points
         */
        foreach(\Results::getEventResults($event) AS $position => $result) {
            $points[$result['driver']->id] = [
                'driver' => $result['driver'],
                'stageTimes' => $result['stage'],
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
        foreach($event->stages AS $stage) {
            foreach($stage->results AS $result) {
                $points[$result->driver->id]['stagePoints'][$stage->order] =
                    isset($system['stage'][$result->position]) && !$result->dnf
                        ? $system['stage'][$result->position]
                        : 0;
            }
        }

        // Sum event points and stage points to get total points
        foreach($points AS $driverID => $point) {
            $points[$driverID]['total']['points'] = $point['eventPoints'];
            foreach($point['stagePoints'] AS $stagePoint) {
                $points[$driverID]['total']['points'] += $stagePoint;
            }
        }

        // Sort by points and position
        usort($points, function($a, $b) {
            if ($a['total']['points'] != $b['total']['points']) {
                return $b['total']['points'] - $a['total']['points'];
            } else {
                return $a['eventPosition'] - $b['eventPosition'];
            }
        });

        return $points;
    }

    /**
     * Get points for the given system for the given season
     * @param PointsSystem $system
     * @param Season $season
     * @return array
     */
    public function forSeason(PointsSystem $system, Season $season)
    {
        $points = [];
        foreach($season->events AS $event) {
            if ($event->closes < $event->last_import) {
                foreach ($this->forEvent($system, $event) AS $position => $result) {
                    $points[$result['driver']->id]['driver'] = $result['driver'];
                    $points[$result['driver']->id]['points'][$event->id] = $result['total']['points'];
                    $points[$result['driver']->id]['positions'][] = $position;
                }
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Get overall points for the given system (on the given collection of seasons)
     * @param PointsSystem $system
     * @param Collection $seasons
     * @return array
     */
    public function overall(PointsSystem $system, Collection $seasons)
    {
        $points = [];
        // Step through the seasons and pull in results
        foreach($seasons AS $season) {
            foreach($this->forSeason($system, $season) AS $position => $result) {
                $points[$result['driver']->id]['driver'] = $result['driver'];
                $points[$result['driver']->id]['points'][$season->id] = $result['total'];
                $points[$result['driver']->id]['positions'][] = $position;
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
            if ($pos > 0 && $this->arePointsEqual($point, $points[$pos-1])) {
                $points[$pos]['position'] = '=';
            } else {
                $points[$pos]['position'] = $pos + 1;
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
        if ($a['total'] != $b['total']) {
            return $b['total'] - $a['total'];
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
