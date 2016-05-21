<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtPointsSystem;
use App\Models\Nation;
use Illuminate\Database\Eloquent\Collection;

class NationPoints extends DriverPoints
{
    /**
     * Get event points and work out average points per nation
     * @param DirtEvent $event
     * @return array
     */
    public function forEvent(DirtEvent $event)
    {
        $points = [];

        if ($event->isComplete()) {
            $driverResults = parent::forEvent($event);

            foreach ($driverResults AS $driver) {
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

            foreach ($points AS $ref => $point) {
                $points[$ref]['total']['sum'] = array_sum($point['points']);
                $points[$ref]['total']['points'] = $points[$ref]['total']['sum'] / count($point['points']);
                $points[$ref]['sortedPositions'] = $points[$ref]['positions'];
                sort($points[$ref]['sortedPositions']);
            }

            usort($points, [$this, 'pointsSort']);

            $points = \Positions::addToArray($points, [$this, 'areEventPointsEqual']);
        }

        return $points;
    }

    public function areEventPointsEqual($a, $b)
    {
        return ($a['total']['points'] == $b['total']['points']);
    }

    /**
     * Get points for the given system for each event in the given championship
     * @param Collection $season
     * @return array
     */
    public function overview(Collection $seasons)
    {
        $points = [];
        foreach($seasons AS $season) {
            foreach ($season->events AS $event) {
                if ($event->isComplete()) {
                    foreach ($this->forEvent($event) AS $position => $result) {
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
     * Get the points for drivers for the given nation
     * @param DirtEvent $event
     * @param Nation $nation
     * @return array
     */
    public function details(DirtEvent $event, Nation $nation)
    {
        $driverResults = \DirtRallyDriverPoints::forEvent($event);

        $results = [];
        foreach($driverResults AS $result) {
            if ($result['entity']->nation->id == $nation->id) {
                $results[] = $result;
            }
        }

        return $results;
    }

}