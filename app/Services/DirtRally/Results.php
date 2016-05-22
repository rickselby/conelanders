<?php

namespace App\Services\DirtRally;

use App\Models\Driver;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtResult;

class Results
{
    public function getEventResults(DirtEvent $event)
    {
        $results = [];
        foreach($event->stages AS $stage) {
            foreach($stage->results AS $result) {
                $results[$result->driver->id]['driver'] = $result->driver;
                $results[$result->driver->id]['stage'][$stage->order] =
                    $result->dnf ? 'DNF' : $result->time;
                $results[$result->driver->id]['stagePositions'][$stage->id] = $result->position;
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

        foreach($event->positions as $position) {
            $results[$position->driver->id]['position'] = $position->position;
        }

        usort($results, function($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $results;
    }

    public function getStageResults($stageID)
    {
        return DirtResult::with('driver')->where('dirt_stage_id', $stageID)->orderBy('position')->get()->toArray();
    }

    public function forDriver(Driver $driver)
    {
        $results['all'] = $this->getAllForDriver($driver);
        $results['best'] = $this->getBestForDriver($results['all']);

        return $results;
    }

    protected function getAllForDriver(Driver $driver)
    {
        $driver->load('dirtResults.stage.event.season.championship');

        $championships = [];

        $results = $driver->dirtResults->sortBy(function($result) {
            return $result->stage->event->closes.'-'.$result->stage->order;
        });

        foreach($results AS $result) {

            $championshipID = $result->stage->event->season->championship->id;
            $seasonID = $result->stage->event->season->id;
            $eventID = $result->stage->event->id;
            $stageID = $result->stage->id;

            if (!isset($championships[$championshipID])) {
                // Load back down the chain
                $result->stage->event->season->championship->seasons->load('events.stages.results.driver', 'events.positions.driver');
                $points = \DirtRallyDriverPoints::overall($result->stage->event->season->championship->seasons);
                $points = array_where($points, function($key, $value) use ($driver) {
                    return $value['entity']->id == $driver->id;
                });
                $driverPoints = array_pop($points);

                $championships[$championshipID] = [
                    'championship' => $result->stage->event->season->championship,
                    'position' => $result->stage->event->season->championship->isComplete()
                        ? $driverPoints['position']
                        : NULL,
                    'seasonPositions' => $driverPoints['positions'],
                    'seasons' => [],
                ];
            }

            if (!isset($championships[$championshipID]['seasons'][$seasonID])) {
                $championships[$championshipID]['seasons'][$seasonID] = [
                    'season' => $result->stage->event->season,
                    'position' => isset($championships[$championshipID]['seasonPositions'][$seasonID]) && $result->stage->event->season->isComplete()
                        ? $championships[$championshipID]['seasonPositions'][$seasonID]
                        : null,
                    'events' => [],
                ];
            }
            if (!isset($championships[$championshipID]['seasons'][$seasonID]['events'][$eventID])) {
                $championships[$championshipID]['seasons'][$seasonID]['events'][$eventID] = [
                    'event' => $result->stage->event,
                    'result' => $result->stage->event->isComplete()
                        ? $result->stage->event->positions()->where('driver_id', $driver->id)->first()
                        : null,
                    'stages' => [],
                ];
            }
            if ($result->stage->event->isComplete()) {
                // Only one result per stage, so just add it now
                $championships[$championshipID]['seasons'][$seasonID]['events'][$eventID]['stages'][$stageID] = [
                    'stage' => $result->stage,
                    'result' => $result,
                ];
            }
        }

        return $championships;
    }

    protected function getBestForDriver($results)
    {
        $bests = [
            'championship' => [],
            'season' => [],
            'event' => [],
            'stage' => [],
        ];

        foreach($results AS $champID => $championship) {
            foreach($championship['seasons'] AS $seasonID => $season) {
                foreach($season['events'] AS $eventID => $event) {
                    foreach ($event['stages'] AS $stageID => $stage) {
                        $this->getBest($bests['stage'], $stage);
                    }
                    $this->getBest($bests['event'], $event);
                }
                $this->getBest($bests['season'], $season);
            }
            $this->getBest($bests['championship'], $championship);
        }

        return $bests;
    }

    protected function getBest(&$current, $new)
    {
        $newPosition = array_key_exists('result', $new)
            ? (isset($new['result']) ? $new['result']->position : NULL)
            : $new['position'];

        if ($newPosition === NULL) {
            return;
        }

        if (!isset($current['best']) || $current['best'] > $newPosition) {
            $current['best'] = $newPosition;
            $current['things'] = collect([$new]);
        } elseif ($current['best'] == $newPosition) {
            $current['things']->push($new);
        }
    }

}
