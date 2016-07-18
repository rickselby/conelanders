<?php

namespace App\Services;

use App\Events\CurrentNewsRequest;
use App\Events\PastNewsRequest;
use App\Events\UpcomingNewsRequest;
use Carbon\Carbon;

class News
{
    /**
     * Get the past news
     * @return array
     */
    public function getPast()
    {
        // Get the news items from the past month
        $newsList = $this->mergeList(
            \Event::fire(new PastNewsRequest(Carbon::now()->subMonth(), Carbon::now()))
        );

        // Sort by timestamp, most recent first
        krsort($newsList);
        
        return $newsList;
    }

    public function getUpcoming()
    {
        $newsList = $this->mergeList(
            \Event::fire(new UpcomingNewsRequest(Carbon::now(), Carbon::now()->addWeek()))
        );

        ksort($newsList);

        return $newsList;
    }
    
    public function getCurrent()
    {
        return $this->mergeList(
            \Event::fire(new CurrentNewsRequest())
        );
    }

    /**
     * Merge the lists of news items, keyed by timestamp
     * @param $news
     * @return array
     */
    private function mergeList($news)
    {
        $newsList = [];
        foreach($news AS $source) {
            foreach($source AS $time => $item) {
                if (!isset($newsList[$time])) {
                    $newsList[$time] = [];
                }
                $newsList[$time][] = $item;
            }
        }
        return $newsList;
    }

}