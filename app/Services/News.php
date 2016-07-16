<?php

namespace App\Services;

use App\Events\NewsRequest;
use Carbon\Carbon;

class News
{
    function get()
    {
        $news = \Event::fire(new NewsRequest(Carbon::now()->subMonth(), Carbon::now()));

        // Merge the lists of news
        $newsList = [];
        foreach($news AS $source) {
            foreach($source AS $time => $item) {
                if (!isset($newsList[$time])) {
                    $newsList[$time] = [];
                }
                $newsList[$time][] = $item;
            }
        }

        // Sort by timestamp, most recent first
        krsort($newsList);
        
        return $newsList;
    }

}