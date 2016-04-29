<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtStage;

class Positions
{
    /**
     * Get stage results and apply positions
     * @param DirtStage $stage
     */
    public function updateStagePositions(DirtStage $stage)
    {
        // Most of the work is done by the ordering
        $results = $stage->results()->orderBy('dnf')->orderBy('time')->get();
        $position = 1;
        $lastTime = 0;
        $lastPosition = 0;
        $bestTime = null;
        foreach($results AS $result) {
            if (!$bestTime) {
                $bestTime = $result->time;
                $result->behind = 0;
            } else {
                $result->behind = $result->time - $bestTime;
            }

            $result->position = ($lastTime == $result->time ? $lastPosition : $position);
            $result->save();

            $lastTime = $result->time;
            $lastPosition = $result->position;

            $position++;
        }
    }

    /**
     * Build event results and apply positions
     * @param DirtEvent $event
     */
    public function updateEventPositions(DirtEvent $event)
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
        $lastTime = 0;
        $lastPosition = 0;
        foreach($times AS $driverID => $detail) {
            $positionToUse = ($detail['time'] == $lastTime) ? $lastPosition : $position;
            $event->positions()->create([
                'driver_id' => $driverID,
                'position' => $positionToUse
            ]);
            $lastTime = $detail['time'];
            $lastPosition = $positionToUse;
            $position++;
        }
    }

    /**
     * @param [] $array
     * @param \Closure $equalFunction
     */
    public function addToArray($array, $equalFunction)
    {
        $position = 1;

        $arrayKeys = array_keys($array);

        foreach($arrayKeys AS $index => $key) {
            $array[$key]['position'] = $position++;

            // See if the previous result is the same as this result
            if ($index > 0 && $equalFunction($array[$key], $array[$arrayKeys[$index-1]])) {
                // If it is, copy the position from the previous result
                $array[$key]['position'] = $array[$arrayKeys[$index-1]]['position'];
            }
        }

        return $array;
    }

    /**
     * Add equals symbols to positions in results for display
     * @param [] $results
     * @return []
     */
    public function addEquals($array)
    {
        $arrayKeys = array_keys($array);

        foreach($arrayKeys AS $index => $key) {

            // See if the previous or next position is the same as this position
            if (
                ($index > 0 && ($array[$key]['position'] == $array[$arrayKeys[$index-1]]['position']))
                    ||
                (isset($arrayKeys[$index+1]) && ($array[$key]['position'] == $array[$arrayKeys[$index+1]]['position']))
            ) {
                // If it is, append an = to the number
                $array[$key]['position'] .= '=';
            }
        }

        return $array;
    }

}