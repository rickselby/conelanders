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
        foreach(\Results::getEventResults($event->id) AS $position => $result) {
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

    public function overall(PointsSystem $system, Collection $seasons)
    {
        $points = [];
        foreach($seasons AS $season) {
            foreach($this->forSeason($system, $season) AS $position => $result) {
                $points[$result['driver']->id]['driver'] = $result['driver'];
                $points[$result['driver']->id]['seasons'][$season->id] = $result['total'];
            }
        }

        foreach($points AS $driverID => $point) {
            $points[$driverID]['total'] = array_sum($point['seasons']);
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

}
