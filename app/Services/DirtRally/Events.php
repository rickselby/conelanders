<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;
use Carbon\Carbon;

class Events
{
    public function getCurrent()
    {
        return DirtEvent::with('season.championship')
            ->where('opens', '<', Carbon::now())
            ->where('closes', '>', Carbon::now())
            ->get();
    }

    public function getNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(DirtEvent::with('stages', 'season.championship')->get() AS $event) {
            if ($event->isComplete() && $event->completeAt->between($start, $end)) {
                if (!isset($news[$event->completeAt->timestamp])) {
                    $news[$event->completeAt->timestamp] = [];
                }
                $news[$event->completeAt->timestamp][] = $event;
            }
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('dirt-rally.event.news', ['events' => $events])->render();
        }
        return $views;
    }
    
}