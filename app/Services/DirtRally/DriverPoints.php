<?php

namespace App\Services\DirtRally;

use App\Interfaces\DirtRally\DriverPointsInterface;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Services\DirtRally\Traits\Points;

class DriverPoints implements DriverPointsInterface
{
    use Points;

    /**
     * {@inheritdoc}
     */
    public function forEvent(DirtEvent $event)
    {
        $points = [];

        if ($event->isComplete()) {
            $system['event'] = \PointSequences::get($event->season->championship->eventPointsSequence);
            $system['stage'] = \PointSequences::get($event->season->championship->stagePointsSequence);
            /**
             * Get the results for this event, and mangle them into points
             */
            foreach (\DirtRallyResults::getEventResults($event) AS $result) {
                $points[$result['driver']->id] = [
                    'entity' => $result['driver'],
                    'stageTimesByOrder' => $result['stage'],
                    'dnf' => $result['dnf'],
                    'total' => [
                        'time' => $result['total'],
                        'points' => 0
                    ],
                    'stagePoints' => [],
                    'stagePositions' => [],
                    'eventPosition' => $result['position'],
                    'eventPoints' => (isset($system['event'][$result['position']]) && !$result['dnf'] && $result['total'])
                        ? $system['event'][$result['position']]
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
                    $points[$result->driver->id]['stagePositions'][$stage->id] = $result->position;
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
                unset($points[$driverID]['stageTimesByOrder']);
            }

            // Sort by points and position
            usort($points, function ($a, $b) {
                if ($a['total']['points'] != $b['total']['points']) {
                    return $b['total']['points'] - $a['total']['points'];
                } else {
                    return $a['eventPosition'] - $b['eventPosition'];
                }
            });

            $points = \Positions::addToArray($points, [$this, 'areEventPointsEqual']);
        }

        return $points;
    }

    /**
     * Check two event results to see if they are equal
     * @param $a
     * @param $b
     * @return bool
     */
    public function areEventPointsEqual($a, $b)
    {
        return ($a['total']['points'] == $b['total']['points'])
            && ($a['eventPosition'] == $b['eventPosition']);
    }

    /**
     * {@inheritdoc}
     */
    public function overview(DirtChampionship $championship)
    {
        $points = [];
        $championship->load([
            'seasons.events.stages.results.driver',
            'seasons.events.positions.driver',
            'seasons.events.season.championship.eventPointsSequence',
            'seasons.events.season.championship.stagePointsSequence',
        ]);
        foreach($championship->seasons AS $season) {
            foreach ($season->events AS $event) {
                if ($event->isComplete()) {
                    foreach (\DirtRallyDriverPoints::forEvent($event) AS $result) {
                        foreach($result['stagePoints'] AS $stage => $stagePoints) {
                            $points[$result['entity']->id]['stages'][$stage] = $stagePoints;
                        }
                        $points[$result['entity']->id]['events'][$event->id] = $result['eventPoints'];
                        $points[$result['entity']->id]['entity'] = $result['entity'];
                        $points[$result['entity']->id]['points'][$event->id] = $result['total']['points'];
                        $points[$result['entity']->id]['positions'][] = $result['position'];
                    }
                }
            }
        }

        return $this->sumAndSort($points);
    }
}
