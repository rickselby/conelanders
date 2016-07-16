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

    public function getNews()
    {
        $news = [];
        foreach(DirtEvent::with('stages', 'season.championship')->get() AS $event) {
            if ($event->isComplete()) {
                if (!isset($news[$event->last_import->timestamp])) {
                    $news[$event->last_import->timestamp] = [];
                }
                $news[$event->last_import->timestamp][] = $event;
            }
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('dirt-rally.event.news', ['events' => $events])->render();
        }
        return $views;
    }
    
}