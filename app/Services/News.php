<?php

namespace App\Services;

use App\Events\NewsRequest;
use Carbon\Carbon;

class News
{
    function get()
    {
        $news = \Event::fire(new NewsRequest());
        // need to merge and order things here
        $newsList = [];
        foreach($news AS $source) {
            foreach($source AS $time => $item) {
                if ($time < Carbon::now()->timestamp && $time > Carbon::now()->subWeek(2)->timestamp) {
                    if (!isset($newsList[$time])) {
                        $newsList[$time] = [];
                    }
                    $newsList[$time][] = $item;
                }
            }
        }
        krsort($newsList);
        return $newsList;
    }

}