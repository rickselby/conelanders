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
                'eventPoints' => (isset($system['event'][$position]) && !$result['dnf']) 
                    ? $system['event'][$position] 
                    : 0,
            ];
        }

        foreach($event->stages AS $stage) {
            foreach($stage->results AS $result) {
                $points[$result->driver->id]['stagePoints'][$stage->order] =
                    isset($system['stage'][$result->position]) && !$result->dnf
                        ? $system['stage'][$result->position]
                        : 0;
            }
        }

        foreach($points AS $driverID => $point) {
            $points[$driverID]['total']['points'] = $point['eventPoints'];
            foreach($point['stagePoints'] AS $stagePoint) {
                $points[$driverID]['total']['points'] += $stagePoint;
            }
        }

        usort($points, function($a, $b) {
            if ($a['total']['points'] != $b['total']['points']) {
                return $b['total']['points'] - $a['total']['points'];
            } else {
                return $a['eventPosition'] - $b['eventPosition'];
            }
        });

        return $points;
    }

    public function forSeason(PointsSystem $system, Season $season)
    {
        $points = [];
        foreach($season->events AS $event) {
            if ($event->closes < $event->last_import) {
                foreach ($this->forEvent($system, $event) AS $position => $result) {
                    $points[$result['driver']->id]['events'][$event->id] = $result['total']['points'];
                    $points[$result['driver']->id]['driver'] = $result['driver'];
                }
            }
        }

        foreach($points AS $driverID => $point) {
            $points[$driverID]['total'] = array_sum($point['events']);
        }

        usort($points, function($a, $b) {
            if ($a['total'] != $b['total']) {
                return $b['total'] - $a['total'];
            } else {
                // something more clever here? maybe?
                return 0;
            }
        });

        return $points;
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
                $points[$result['driver']->id]['seasons'][$season->id] = $result['total'];
                $points[$result['driver']->id]['positions'][] = $position;
            }
        }

        // Step through each driver, sum their points, and sort their positions
        foreach($points AS $driverID => $point) {
            $points[$driverID]['total'] = array_sum($point['seasons']);
            sort($points[$driverID]['positions']);
        }

        // Sort the drivers
        $this->overallPointsSort($points, $seasons);

        // Step through the drivers and set their positions.
        // If a driver is equal to the one above, set as equal.
        foreach($points AS $pos => $point) {
            if ($pos > 0 && $this->areOverallPointsEqual($point, $points[$pos-1])) {
                $points[$pos]['position'] = '=';
            } else {
                $points[$pos]['position'] = $pos + 1;
            }
        }

        return $points;
    }

    /**
     * Sort overall points
     * @param array $points
     * @param Collection $seasons
     */
    protected function overallPointsSort(&$points, $seasons)
    {
        usort($points, function($a, $b) use ($seasons) {
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

            // If they have identical finishing positions, sort by events entered; earlier events take priority
            foreach ($seasons AS $season) {
                if (isset($a['seasons'][$season->id]) && !isset($b['seasons'][$season->id])) {
                    return -1;
                } elseif (!isset($a['seasons'][$season->id]) && isset($b['seasons'][$season->id])) {
                    return 1;
                }
            }

            // There's nothing more we can do!
            return 0;
        });
    }

    protected function areOverallPointsEqual($a, $b)
    {
        return $a['total'] == $b['total']
            && $a['positions'] == $b['positions']
            && count($a['seasons']) == count($b['seasons']);
    }

}
