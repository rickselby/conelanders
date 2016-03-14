<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Result;
use App\Models\Stage;

class Results
{
    public function getEventResults($eventID)
    {
        $event = Event::with('stages.results.driver')->find($eventID);
        $results = [];
        foreach($event->stages AS $stage) {
            foreach($stage->results AS $result) {
                $results[$result->driver->id]['driver'] = $result->driver;
                $results[$result->driver->id]['stage'][$stage->order] = $result->time;
            }
        }

        foreach($results AS $id => $result) {
            if (count($result['stage']) == count($event->stages)) {
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
            if ($a['total'] == null) {
                return 1;
            } elseif ($b['total'] == null) {
                return -1;
            } else {
                return $a['total'] - $b['total'];
            }
        });

        return $results;
    }

    public function getStageResults($stageID)
    {
        // I want to do more with this...
        return Result::with('driver')->where('stage_id', $stageID)->orderBy('time')->get();
    }
}