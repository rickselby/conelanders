<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;

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

}