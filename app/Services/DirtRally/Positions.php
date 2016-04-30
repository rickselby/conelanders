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
        $results = [];
        // Drop the results into an array so positions can be added
        foreach($stage->results()->orderBy('dnf')->orderBy('time')->get() AS $result) {
            $results[] = [
                'result' => $result,
            ];
        }

        // Add positions to the array
        $results = $this->addToArray($results, function($a, $b) {
            return $a['result']->time == $b['result']->time;
        });

        // Save positions back to the models
        foreach($results AS $result) {
            $result['result']->position = $result['position'];
            $result['result']->save();
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

        $times = $this->addToArray($times, function($a, $b) {
            return ($a['stages'] == $b['stages']) && ($a['time'] == $b['time']);
        });

        foreach($times AS $driverID => $detail) {
            $event->positions()->create([
                'driver_id' => $driverID,
                'position' => $detail['position'],
            ]);
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