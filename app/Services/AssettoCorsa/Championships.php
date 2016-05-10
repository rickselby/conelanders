<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use Illuminate\Support\Collection;

class Championships
{
    /**
     * Get the current active championship
     * @return AcChampionship|null
     */
    public function getCurrent()
    {
        foreach(AcChampionship::all()->sortByDesc('ends') AS $championship) {
            if (!$championship->isComplete()) {
                return $championship;
            }
        }
        return null;
    }

    /**
     * Get all complete championships
     * @return AcChampionship[]
     */
    public function getComplete()
    {
        $championships = [];
        foreach(AcChampionship::all()->sortByDesc('ends') AS $championship) {
            if ($championship->isComplete()) {
                $championships[] = $championship;
            }
        }
        return $championships;
    }

}