<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcPointsSystem;

class PointsSystems
{
    public function forSystem(AcPointsSystem $system)
    {
        $points = ['race' => [], 'laps' => []];
        foreach($system->raceSequence->points AS $point) {
            $points['race'][$point->position] = $point->points;
        }
        foreach($system->lapsSequence->points AS $point) {
            $points['laps'][$point->position] = $point->points;
        }
        return $points;
    }
}
