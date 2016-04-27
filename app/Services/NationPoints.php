<?php

namespace App\Services;

use App\Models\DirtRally\Event;
use App\Models\DirtRally\PointsSystem;
use Illuminate\Database\Eloquent\Collection;

class NationPoints extends DriverPoints
{
    /**
     * Get event points and work out average points per nation
     * @param PointsSystem $system
     * @param Event $event
     * @return array
     */
    public function forEvent(PointsSystem $system, Event $event)
    {
        $driverResults = parent::forEvent($system, $event);

        $points = [];
        foreach($driverResults AS $driver) {
            $nationID = $driver['entity']->nation->id;
            if (!isset($points[$nationID])) {
                $points[$nationID] = [
                    'entity' => $driver['entity']->nation,
                    'total' => [
                        'points' => 0
                    ],
                    'points' => [],
                    'positions' => [],
                ];
            }
            $points[$nationID]['points'][] = $driver['total']['points'];
            $points[$nationID]['positions'][] = $driver['eventPosition'];
        }

        foreach($points AS $ref => $point) {
            $points[$ref]['total']['sum'] = array_sum($point['points']);
            $points[$ref]['total']['points'] = $points[$ref]['total']['sum'] / count($point['points']);
        }

        usort($points, [$this, 'pointsSort']);

        return $points;
    }

    /**
     * Get points for the given system for each event in the given championship
     * @param PointsSystem $system
     * @param Season $season
     * @return array
     */
    public function overview(PointsSystem $system, Collection $seasons)
    {
        $points = [];
        foreach($seasons AS $season) {
            foreach ($season->events AS $event) {
                if ($event->isComplete()) {
                    foreach ($this->forEvent($system, $event) AS $position => $result) {
                        $points[$result['entity']->id]['entity'] = $result['entity'];
                        $points[$result['entity']->id]['points'][$event->id] = $result['total']['points'];
                        $points[$result['entity']->id]['positions'][] = $position;
                    }
                }
            }
        }

        return $this->sumAndSort($points);
    }

}