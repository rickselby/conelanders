<?php

namespace App\Services\DirtRally;

use App\Interfaces\DirtRally\TimesInterface;
use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;

class Times implements TimesInterface
{

    public function forEvent(DirtEvent $event)
    {
        $times = [];

        $worstTimes = [
            'stages' => [],
            'overall' => [],
        ];

        /**
         * Get the results for this event, and mangle them a bit
         */
        foreach(\DirtRallyResults::getEventResults($event) AS $result) {
            $times[$result['driver']->id] = [
                'driver' => $result['driver'],
                'stageTimes' => $result['stage'],
                'stagePositions' => $result['stagePositions'],
                'dnf' => $result['dnf'],
                'total' => $result['total'],
            ];
            foreach($result['stage'] as $stageRef => $time) {
                $worstTimes['stages'][$stageRef][] = $time;
            }
            $worstTimes['overall'][] = $result['total'];
        }

        $worstTime['overall'] = max($worstTimes['overall']);
        foreach($worstTimes['stages'] AS $stageRef => $timeList) {
            $worstTime['stage'][$stageRef] = max($timeList);
        }

        foreach($times AS $driverID => $detail) {
            if ($detail['dnf']) {
                $times[$driverID]['total'] = $worstTime['overall'] + $this->dnfPenalty();
            } else {
                foreach ($event->stages AS $stage) {
                    if ($detail['stageTimes'][$stage->order] == null) {
                        $times[$driverID]['stageTimes'][$stage->order] = $worstTime['stage'][$stage->order];
                        $times[$driverID]['worst'][$stage->order] = true;
                        $times[$driverID]['total'] = array_sum($times[$driverID]['stageTimes']);
                    }
                }
            }
        }

        usort($times, function($a, $b) {
            return $a['total'] - $b['total'];
        });

        return [
            'times' => \Positions::addToArray($times, [$this, 'areTimesEqual']),
            'dnf' => $worstTime['overall'] + $this->dnfPenalty(),
        ];
    }

    public function forSeason(DirtSeason $season)
    {
        $times = [];
        $events = [];
        $dnf = 0;
        foreach($season->events AS $event) {
            if ($event->isComplete()) {
                $events[$event->id] = $this->forEvent($event);
                $dnf += $events[$event->id]['dnf'];
                foreach ($events[$event->id]['times'] AS $result) {
                    $times[$result['driver']->id]['events'][$event->id] = $result['total'];
                    $times[$result['driver']->id]['eventPositions'][$event->id] = $result['position'];
                    $times[$result['driver']->id]['dnfs'][$event->id] = $result['dnf'];
                    $times[$result['driver']->id]['dnss'][$event->id] = false;
                    $times[$result['driver']->id]['driver'] = $result['driver'];
                }
            }
        }

        foreach($times AS $driverID => $detail) {
            foreach($season->events AS $event) {
                if (!isset($detail['events'][$event->id])) {
                    if ($event->closes < $event->last_import) {
                        $times[$driverID]['events'][$event->id] = $events[$event->id]['dnf'];
                        $times[$driverID]['dnfs'][$event->id] = true;
                        $times[$driverID]['dnss'][$event->id] = true;
                    } else {
                        $times[$driverID]['events'][$event->id] = null;
                        $times[$driverID]['dnfs'][$event->id] = null;
                        $times[$driverID]['dnss'][$event->id] = null;
                    }
                }
            }
            $times[$driverID]['total'] = array_sum($times[$driverID]['events']);
        }

        usort($times, function($a, $b) {
            return $a['total'] - $b['total'];
        });

        return [
            'times' => \Positions::addToArray($times, [$this, 'areTimesEqual']),
            'dnf' => $dnf,
        ];
    }

    public function overall(DirtChampionship $championship)
    {
        $championship->load([
            'seasons.events.stages.results.driver',
            'seasons.events.positions.driver',
        ]);
        $times = [];
        $seasonList = [];
        foreach($championship->seasons AS $season) {
            $seasonList[$season->id] = $this->forSeason($season);
            foreach ($seasonList[$season->id]['times'] AS $result) {
                $times[$result['driver']->id]['driver'] = $result['driver'];
                $times[$result['driver']->id]['seasons'][$season->id] = $result['total'];
                $times[$result['driver']->id]['seasonPositions'][$season->id] = $result['position'];
            }
        }

        foreach($times AS $driverID => $detail) {
            foreach($championship->seasons AS $season) {
                if (!isset($detail['seasons'][$season->id])) {
                    $times[$driverID]['seasons'][$season->id] = $seasonList[$season->id]['dnf'];
                    $times[$driverID]['dnss'][$season->id] = true;
                } else {
                    $times[$driverID]['dnss'][$season->id] = false;
                }
            }
            $times[$driverID]['total'] = array_sum($times[$driverID]['seasons']);
        }

        usort($times, function($a, $b) {
            return $a['total'] - $b['total'];
        });

        return \Positions::addToArray($times, [$this, 'areTimesEqual']);
    }

    private function dnfPenalty()
    {
        // ten minutes in milliseconds
        return 10*60*1000;
    }

    public function areTimesEqual($a, $b)
    {
        return $a['total'] == $b['total'];
    }

}
