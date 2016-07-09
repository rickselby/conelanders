<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtChampionship;

class Championships
{
    protected $driverIDs = [];

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

    /**
     * Get all drivers that competed in the given championship
     * @param DirtChampionship $championship
     * @return int[]
     */
    public function getDriversFor(DirtChampionship $championship)
    {
        if (!isset($this->driverIDs[$championship->id])) {
            $this->driverIDs[$championship->id] = \DB::table('dirt_results')
                ->join('dirt_stages', 'dirt_stages.id', '=', 'dirt_results.dirt_stage_id')
                ->join('dirt_events', 'dirt_events.id', '=', 'dirt_stages.dirt_event_id')
                ->join('dirt_seasons', 'dirt_seasons.id', '=', 'dirt_events.dirt_season_id')
                ->select('dirt_results.driver_id')
                ->where('dirt_seasons.dirt_championship_id', '=', $championship->id)
                ->pluck('driver_id');
        }

        return $this->driverIDs[$championship->id];
    }
}
