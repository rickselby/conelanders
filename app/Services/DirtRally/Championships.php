<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtChampionship;

class Championships
{
    private $sorted;

    /**
     * Get a (cached) sorted list of championships
     * @return []
     */
    public function getSorted()
    {
        if (!$this->sorted) {
            $this->sorted = DirtChampionship::with('seasons.events')->get()->sortByDesc('closes');
        }
        return $this->sorted;
    }

    /**
     * Get the current active championship
     * @return DirtChampionship|null
     */
    public function getCurrent()
    {
        foreach($this->getSorted() AS $championship) {
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
        foreach($this->getSorted() AS $championship) {
            if ($championship->isComplete()) {
                $championships[] = $championship;
            }
        }
        return $championships;
    }
}
