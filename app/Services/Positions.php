<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Stage;

class Positions
{
    /**
     * Get stage results and apply positions
     * @param Stage $stage
     */
    public function updateStagePositions(Stage $stage)
    {
        // Most of the work is done by the ordering
        $results = $stage->results()->with('driver')->orderBy('dnf')->orderBy('time')->get();
        $position = 1;
        $bestTime = null;
        foreach($results AS $result) {
            if (!$bestTime) {
                $bestTime = $result->time;
                $result->behind = 0;
            } else {
                $result->behind = $result->time - $bestTime;
            }
            $result->position = $position++;
            $result->save();
        }
    }

    /**
     * Build event results and apply positions
     * @param Event $event
     */
    public function updateEventPositions(Event $event)
    {
        $event->positions()->delete();
        $event->load('stages.results.driver');
        $times = [];
        // Get the total time per driver, and the total number of stages completed
        foreach($event->stages AS $stage) {
            foreach($stage->results AS $result) {
                if (!isset($times[$result->driver->id])) {
                    $times[$result->driver->id] = ['time' => 0, 'stages' => 0];
                }
                if ($result->dnf) {
                    $times[$result->driver->id]['time'] += $stage->long ? ImportAbstract::LONG_DNF : ImportAbstract::SHORT_DNF;
                } else {
                    $times[$result->driver->id]['time'] += $result->time;
                }
                $times[$result->driver->id]['stages']++;
            }
        }

        // Sort the drivers by the number of stages (desc) and time (asc)
        uasort($times, function($a, $b) {
            if ($a['stages'] == $b['stages']) {
                return $a['time'] - $b['time'];
            } else {
                return $b['stages'] - $a['stages'];
            }
        });

        // Set the positions
        $position = 1;
        foreach($times AS $driverID => $detail) {
            $event->positions()->create([
                'driver_id' => $driverID,
                'position' => $position++,
            ]);
        }
    }
}