<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Event;
use App\Models\EventPosition;
use App\Models\PointsSystem;
use App\Models\Result;

class Results
{
    public function getEventResults(Event $event)
    {
        $results = [];
        foreach($event->stages AS $stage) {
            foreach($stage->results AS $result) {
                $results[$result->driver->id]['driver'] = $result->driver;
                $results[$result->driver->id]['stage'][$stage->order] =
                    $result->dnf ? 'DNF' : $result->time;
                if (isset($results[$result->driver->id]['dnf'])) {
                    $results[$result->driver->id]['dnf'] |= $result->dnf;
                } else {
                    $results[$result->driver->id]['dnf'] = $result->dnf;
                }
            }
        }

        foreach($results AS $id => $result) {
            if (count($result['stage']) == count($event->stages) && !$result['dnf']) {
                $results[$id]['total'] = array_sum($result['stage']);
            } else {
                foreach($event->stages AS $stage) {
                    if (!isset($result['stage'][$stage->order])) {
                        $results[$id]['stage'][$stage->order] = null;
                    }
                }
                $results[$id]['total'] = null;
            }
        }

        $return = [];
        foreach($event->positions as $position) {
            $return[$position->position] = $results[$position->driver->id];
        }
        ksort($return);
        return $return;
    }

    public function getStageResults($stageID)
    {
        return Result::with('driver')->where('stage_id', $stageID)->orderBy('position')->get();
    }

    public function forDriver(Driver $driver)
    {
        $driver->load('results.stage.event.season.championship');

        $championships = [];

        foreach($driver->results AS $result) {
            $championshipID = $result->stage->event->season->championship->id;
            $seasonID = $result->stage->event->season->id;
            $eventID = $result->stage->event->id;
            $stageID = $result->stage->id;
            if (!isset($championships[$championshipID])) {
                $points = \DriverPoints::overall(
                    PointsSystem::where('default', true)->first(),
                    $result->stage->event->season->championship->seasons
                );
                $points = array_where($points, function($key, $value) use ($driver) {
                    return $value['entity']->id == $driver->id;
                });
                $driverPoints = array_pop($points);

                $championships[$championshipID] = [
                    'championship' => $result->stage->event->season->championship,
                    'position' => $driverPoints['position'],
                    'seasonPositions' => $driverPoints['seasonPosition'],
                    'seasons' => [],
                ];
            }
            if (!isset($championships[$championshipID]['seasons'][$seasonID])) {
                $championships[$championshipID]['seasons'][$seasonID] = [
                    'season' => $result->stage->event->season,
                    'position' => $championships[$championshipID]['seasonPositions'][$seasonID],
                    'events' => [],
                ];
            }
            if (!isset($championships[$championshipID]['seasons'][$seasonID]['events'][$eventID])) {
                $championships[$championshipID]['seasons'][$seasonID]['events'][$eventID] = [
                    'event' => $result->stage->event,
                    'result' => $result->stage->event->positions()->where('driver_id', $driver->id)->first(),
                    'stages' => [],
                ];
            }
            // Only one result per stage, so just add it now
            $championships[$championshipID]['seasons'][$seasonID]['events'][$eventID]['stages'][] = [
                'stage' => $result->stage,
                'result' => $result,
            ];
        }

        return $championships;
    }
}