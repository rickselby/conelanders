<?php

namespace App\Services\Races;

use App\Interfaces\Races\ChampionshipInterface;
use App\Models\Races\RacesCar;
use App\Models\Races\RacesChampionship;
use Carbon\Carbon;

class Championships implements ChampionshipInterface
{
    /**
     * @inheritdoc
     */
    public function shownBeforeRelease(RacesChampionship $championship)
    {
        if (!$championship->isComplete()) {
            foreach ($championship->events AS $event) {
                if (\RacesEvent::canBeShown($event) && !$event->canBeReleased()) {
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
        $categories = [];

        foreach(RacesChampionship::with('events.sessions.playlist')->get() AS $championship) {
            if ($championship->completeAt && $championship->completeAt->between($start, $end)) {
                if (!isset($news[$championship->category->id])) {
                    $news[$championship->category->id] = [];
                    $categories[$championship->category->id] = $championship->category;
                }
                if (!isset($news[$championship->category->id][$championship->completeAt->timestamp])) {
                    $news[$championship->category->id][$championship->completeAt->timestamp] = [];
                }
                $news[$championship->category->id][$championship->completeAt->timestamp][] = $championship;
            }
        }
        $views = [];
        foreach($news AS $categoryID => $list) {
            foreach($list AS $date => $championships) {
                $views[$date] = [
                    'view' => \View::make('races.championship.news', ['championships' => $championships])->render(),
                    'category' => $categories[$categoryID],
                ];
            }
        }
        return $views;
    }

    /**
     * @inheritdoc
     */
    public function cars(RacesChampionship $championship)
    {
        return RacesCar::forChampionship($championship)->get();
    }

    /**
     * @inheritdoc
     */
    public function multipleCars(RacesChampionship $championship)
    {
        return count($this->cars($championship)) > 1;
    }

}
