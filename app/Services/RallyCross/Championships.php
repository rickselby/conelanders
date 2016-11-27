<?php

namespace App\Services\RallyCross;

use App\Interfaces\RallyCross\ChampionshipInterface;
use App\Models\RallyCross\RxCar;
use App\Models\RallyCross\RxChampionship;
use Carbon\Carbon;

class Championships implements ChampionshipInterface
{
    /**
     * @inheritdoc
     */
    public function shownBeforeRelease(RxChampionship $championship)
    {
        if (!$championship->isComplete()) {
            foreach ($championship->events AS $event) {
                if (\RXEvent::canBeShown($event) && !$event->canBeReleased()) {
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
        foreach(RxChampionship::with('events.sessions')->get() AS $championship) {
            if ($championship->completeAt && $championship->completeAt->between($start, $end)) {
                if (!isset($news[$championship->completeAt->timestamp])) {
                    $news[$championship->completeAt->timestamp] = [];
                }
                $news[$championship->completeAt->timestamp][] = $championship;
            }
        }
        $views = [];
        foreach($news AS $date => $championships) {
            $views[$date] = \View::make('rallycross.championship.news', ['championships' => $championships])->render();
        }
        return $views;
    }

    /**
     * @inheritdoc
     */
    public function cars(RxChampionship $championship)
    {
        return RxCar::forChampionship($championship)->get();
    }

    /**
     * @inheritdoc
     */
    public function multipleCars(RxChampionship $championship)
    {
        return count($this->cars($championship)) > 1;
    }

    public function getUserChampionships()
    {
        return \View::make(
            'rallycross.championship.user',
            ['championships' => \Auth::user()->admin(RxChampionship::class)]
        )->render();
    }

}
