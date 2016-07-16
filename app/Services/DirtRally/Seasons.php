<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtSeason;
use Carbon\Carbon;

class Seasons
{
    /**
     * Get a list of completed seasons between the given dates
     * @return array
     */
    public function getNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(DirtSeason::with('events', 'championship')->get() AS $season) {
            if ($season->isComplete() && $season->completeAt->between($start, $end)) {
                if (!isset($news[$season->completeAt->timestamp])) {
                    $news[$season->completeAt->timestamp] = [];
                }
                $news[$season->completeAt->timestamp][] = $season;
            }
        }
        $views = [];
        foreach($news AS $date => $seasons) {
            $views[$date] = \View::make('dirt-rally.season.news', ['seasons' => $seasons])->render();
        }
        return $views;
    }
}
