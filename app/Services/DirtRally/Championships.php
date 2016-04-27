<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\Championship;

class Championships
{
    /**
     * Get the current active championship
     * @return Championship|null
     */
    public function getCurrent()
    {
        foreach(Championship::all()->sortByDesc('closes') AS $championship) {
            if (!$championship->isComplete()) {
                return $championship;
            }
        }
        return null;
    }

    /**
     * Get all complete championships
     * @return Championship[]
     */
    public function getComplete()
    {
        $championships = [];
        foreach(Championship::all()->sortByDesc('closes') AS $championship) {
            if ($championship->isComplete()) {
                $championships[] = $championship;
            }
        }
        return $championships;
    }
}