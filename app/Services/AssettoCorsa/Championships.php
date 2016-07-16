<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use Illuminate\Support\Collection;

class Championships
{
    /**
     * Check if the current championship has results that are being shown before they're released
     *
     * @param AcChampionship $championship
     * 
     * @return bool
     */
    public function shownBeforeRelease(AcChampionship $championship)
    {
        if (!$championship->isComplete()) {
            foreach ($championship->events AS $event) {
                if (\ACEvent::canBeShown($event) && !$event->canBeReleased()) {
                    return true;
                }
            }
        }
        return false;
    }

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

    /**
     * Get news on completed championships
     * @return array
     */
    public function getNews()
    {
        $news = [];
        foreach(AcChampionship::with('events.sessions')->get() AS $championship) {
            if ($championship->completeAt) {
                if (!isset($news[$championship->completeAt->timestamp])) {
                    $news[$championship->completeAt->timestamp] = [];
                }
                $news[$championship->completeAt->timestamp][] = $championship;
            }
        }
        $views = [];
        foreach($news AS $date => $championships) {
            $views[$date] = \View::make('assetto-corsa.championship.news', ['championships' => $championships])->render();
        }
        return $views;
    }

}