<?php

namespace App\Services;

use App\Models\Event;
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
}