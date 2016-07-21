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

    public function getPastNews(Carbon $start, Carbon $end)
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
            $views[$date] = \View::make('dirt-rally.event.news.past', ['events' => $events])->render();
        }
        return $views;
    }

    public function getUpcomingNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(DirtEvent::with('stages', 'season.championship')->get() AS $event) {
            if ($event->opens->between($start, $end)) {
                if (!isset($news[$event->opens->timestamp])) {
                    $news[$event->opens->timestamp] = [];
                }
                $news[$event->opens->timestamp][] = $event;
            }
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('dirt-rally.event.news.upcoming', ['events' => $events])->render();
        }
        return $views;
    }

    public function getCurrentNews()
    {
        $events = [];
        foreach(DirtEvent::with('stages', 'season.championship')->get() AS $event) {
            if (Carbon::now()->between($event->opens, $event->closes)) {
                $events[] = $event;
            }
        }
        return [
            \View::make('dirt-rally.event.news.current', ['events' => $events])->render()
        ];
    }
}
