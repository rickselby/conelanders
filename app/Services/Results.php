<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Result;

class Results
{
    public function getEventResults($eventID)
    {
        $event = Event::with('stages.results.driver')->find($eventID);
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

        usort($results, function($a, $b) {
            if ($a['total'] === null && $b['total'] === null) {
                return $this->sortDNFs($a, $b);
            } elseif ($a['total'] === null) {
                return 1;
            } elseif ($b['total'] === null) {
                return -1;
            } else {
                return $a['total'] - $b['total'];
            }
        });

        return $results;
    }

    protected function sortDNFs($a, $b) {
        for ($i = count($a['stage']); $i > 0; $i--) {
            if (is_string($a['stage'][$i]) && is_string($b['stage'][$i])) {
                // both strings, loop again
            } elseif ($a['stage'][$i] === null && $b['stage'][$i] === null) {
                // both null, loop again
            } elseif (is_string($a['stage'][$i])) {
                // string comes above empty, but below numbers
                return $b['stage'][$i] === null ? -1 : 1;
            } elseif (is_string($b['stage'][$i])) {
                // string comes above empty, but below numbers
                return $a['stage'][$i] === null ? 1 : -1;
            } elseif ($a['stage'][$i] === null) {
                // null is below a number (string handled above)
                return 1;
            } elseif ($b['stage'][$i] === null) {
                // null is below a number (string handled above)
                return -1;
            } else {
                // I think by now they're both numbers? Sum the drivers' total time?
                return array_sum(array_slice($a['stage'], 0, $i))
                    - array_sum(array_slice($b['stage'], 0, $i));
            }
        }
        return 0;
    }

    public function getStageResults($stageID)
    {
        // I want to do more with this...
        return Result::with('driver')->where('stage_id', $stageID)->orderBy('time')->get();
    }
}