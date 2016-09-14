<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\ChampionshipInterface;
use App\Models\AssettoCorsa\AcCar;
use App\Models\AssettoCorsa\AcChampionship;
use Carbon\Carbon;

class Championships implements ChampionshipInterface
{
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getPastNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(AcChampionship::with('events.sessions.playlist')->get() AS $championship) {
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

    /**
     * @inheritdoc
     */
    public function cars(AcChampionship $championship)
    {
        return AcCar::forChampionship($championship)->get();
    }

}
