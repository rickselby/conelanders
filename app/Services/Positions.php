<?php

namespace App\Services;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtStage;

class Positions
{
    /**
     * @param [] $array
     * @param callable $equalFunction
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

    public function colour($position, $points = NULL)
    {
        if (is_numeric($position)) {
            switch ($position) {
                case 1:
                    return 'position-first';
                case 2:
                    return 'position-second';
                case 3:
                    return 'position-third';
            }
            if ($points !== NULL) {
                if ($points > 0) {
                    return 'position-points';
                } else {
                    return 'position-finished';
                }
            }
        }
        return '';
    }

}