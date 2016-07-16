<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtSeason;

class Seasons
{
    /**
     * Get the times at which seasons are complete
     * @return array
     */
    public function getNews()
    {
        $news = [];
        foreach(DirtSeason::with('events', 'championship')->get() AS $season) {
            if ($season->isComplete()) {
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
