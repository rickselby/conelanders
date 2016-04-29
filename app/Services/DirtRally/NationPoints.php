<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtPointsSystem;
use Illuminate\Database\Eloquent\Collection;

class NationPoints extends DriverPoints
{
    /**
     * Get event points and work out average points per nation
     * @param DirtPointsSystem $system
     * @param DirtEvent $event
     * @return array
     */
    public function forEvent(DirtPointsSystem $system, DirtEvent $event)
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

        $position = 1;
        foreach($points AS $key => $detail) {
            $points[$key]['position'] = $position;
            if($key > 0 && ($detail['total']['points'] == $points[$key-1]['total']['points'])) {
                // If the previous value is the same as this one, use the same position string
                $points[$key]['position'] = $points[$key-1]['position'];
            } elseif (isset($points[$key+1]) && ($detail['total']['points'] == $points[$key+1]['total']['points'])) {
                $points[$key]['position'] .= '=';
            }
            $position++;
        }

        return $points;
    }

    /**
     * Get points for the given system for each event in the given championship
     * @param DirtPointsSystem $system
     * @param Collection $season
     * @return array
     */
    public function overview(DirtPointsSystem $system, Collection $seasons)
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