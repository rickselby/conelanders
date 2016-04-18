<?php

namespace App\Services;

use App\Models\Point;
use App\Models\PointsSequence;
use App\Models\PointsSystem;

class PointSequences
{
    public function forSystem(PointsSystem $system)
    {
        $points = ['event' => [], 'stage' => []];
        foreach($system->eventSequence->points AS $point) {
            $points['event'][$point->position] = $point->points;
        }
        foreach($system->stageSequence->points AS $point) {
            $points['stage'][$point->position] = $point->points;
        }
        return $points;
    }

    public function set(PointsSequence $sequence, $pointsList)
    {
        foreach($pointsList AS $position => $points) {
            // Get the current points, or create...
            /** @var Point $point */
            $point = $sequence->points->where('position', $position)->first();
            if (!$point) {
                // Create a new point, if it's not zero
                if ($points != 0) {
                    $point = new Point(['position' => $position, 'points' => $points]);
                    $sequence->points()->save($point);
                }
            } else {
                // Update points, or delete if zero
                if ($points != 0) {
                    $point->points = $points;
                    $point->save();
                } else {
                    $point->delete();
                }
            }
        }
    }
}
