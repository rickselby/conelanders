<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtChampionship;

class Championships
{
    /**
     * Get the current active championship
     * @return DirtChampionship|null
     */
    public function getCurrent()
    {
        foreach(DirtChampionship::with('seasons.events')->get()->sortBy('closes') AS $championship) {
            if (!$championship->isComplete()) {
                return $championship;
            }
        }
        return null;
    }

    /**
     * Get all complete championships
     * @return DirtChampionship[]
     */
    public function getComplete()
    {
        $championships = [];
        foreach(DirtChampionship::with('seasons.events')->get()->sortByDesc('closes') AS $championship) {
            if ($championship->isComplete()) {
                $championships[] = $championship;
            }
        }
        return $championships;
    }
}
