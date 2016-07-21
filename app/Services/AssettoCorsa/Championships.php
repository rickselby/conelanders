<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use Carbon\Carbon;

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
     * Get news on completed championships
     * @return array
     */
    public function getPastNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(AcChampionship::with('events.sessions')->get() AS $championship) {
            if ($championship->completeAt && $championship->completeAt->between($start, $end)) {
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
