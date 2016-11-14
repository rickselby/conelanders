<?php

namespace App\Services\Races;

use App\Interfaces\Races\EventInterface;
use App\Models\Races\RacesEvent;
use Carbon\Carbon;

class Event implements EventInterface
{

    protected $driverIDs = [];

    /**
     * {@inheritdoc}
     */
    public function canBeShown(RacesEvent $event)
    {
        return $event->canBeReleased() || \RacesEvent::currentUserInEvent($event);
    }

    /**
     * {@inheritdoc}
     */
    public function currentUserInEvent(RacesEvent $event)
    {
        if (\Auth::check() && \Auth::user()->driver) {
            return in_array(\Auth::user()->driver->id, \RacesEvent::getDriverIDs($event));
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverIDs(RacesEvent $event)
    {
        if (!isset($this->driverIDs[$event->id])) {
            $this->driverIDs[$event->id] = \DB::table('races_championship_entrants')
                ->join('races_session_entrants', 'races_championship_entrants.id', '=', 'races_session_entrants.races_championship_entrant_id')
                ->join('races_sessions', 'races_session_entrants.races_session_id', '=', 'races_sessions.id')
                ->select('races_championship_entrants.driver_id')
                ->where('races_sessions.races_event_id', '=', $event->id)
                ->pluck('driver_id');
        }

        return $this->driverIDs[$event->id];
    }

    /**
     * {@inheritdoc}
     */
    public function getPastNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(RacesEvent::with('sessions.playlist', 'championship')->get() AS $event) {
            foreach($event->sessions AS $session) {
                if ($session->release && $session->release->between($start, $end)) {
                    if (!isset($news[$session->release->timestamp])) {
                        $news[$session->release->timestamp] = [];
                    }
                    if (!isset($news[$session->release->timestamp][$event->id])) {
                        $news[$session->release->timestamp][$event->id] = [
                            'event' => $event,
                            'sessions' => [],
                        ];
                    }
                    $news[$session->release->timestamp][$event->id]['sessions'][] = $session;
                }
            }
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('races.event.news.results.past', ['events' => $events])->render();
        }
        return $views;
    }
    
    public function getUpcomingNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(RacesEvent::with('sessions', 'championship')->get() AS $event) {
            foreach($event->sessions AS $session) {
                if ($session->release && $session->release->between($start, $end)) {
                    if (!isset($news[$session->release->timestamp])) {
                        $news[$session->release->timestamp] = [];
                    }
                    if (!isset($news[$session->release->timestamp][$event->id])) {
                        $news[$session->release->timestamp][$event->id] = [
                            'event' => $event,
                            'sessions' => [],
                        ];
                    }
                    $news[$session->release->timestamp][$event->id]['sessions'][] = $session;
                }
            }            
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('races.event.news.results.upcoming', ['events' => $events])->render();
        }
        return $views;
    }

    /**
     * Get upcoming events for the logged in user
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     */
    public function getUpcomingEvents(Carbon $start, Carbon $end)
    {
        $news = [];
        if (\Auth::check() && \Auth::user()->driver) {
            foreach(\Auth::user()->driver->acEntries AS $entry) {
                foreach($entry->championship->events AS $event) {
                    if ($event->time && $event->time->between($start, $end)) {
                        if (!isset($news[$event->time->timestamp])) {
                            $news[$event->time->timestamp] = [];
                        }
                        $news[$event->time->timestamp][$event->id] = $event;
                    }
                }
            }
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('races.event.news.dates', ['events' => $events])->render();
        }
        return $views;
    }
}
