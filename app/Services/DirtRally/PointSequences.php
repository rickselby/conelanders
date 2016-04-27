<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtPointsSystem;

class PointSequences
{
    public function forSystem(DirtPointsSystem $system)
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
}
