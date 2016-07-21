<?php

namespace App\Services;

use App\Events\News\RequestCurrent;
use App\Events\News\RequestPast;
use App\Events\News\RequestUpcoming;
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
            \Event::fire(new RequestPast(Carbon::now()->subMonth(), Carbon::now()))
        );

        // Sort by timestamp, most recent first
        krsort($newsList);
        
        return $newsList;
    }

    public function getUpcoming()
    {
        $newsList = $this->mergeList(
            \Event::fire(new RequestUpcoming(Carbon::now(), Carbon::now()->addWeek()))
        );

        ksort($newsList);

        return $newsList;
    }
    
    public function getCurrent()
    {
        return $this->mergeList(
            \Event::fire(new RequestCurrent())
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