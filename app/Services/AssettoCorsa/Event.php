<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\EventInterface;
use App\Models\AssettoCorsa\AcEvent;
use Carbon\Carbon;

class Event implements EventInterface
{

    protected $driverIDs = [];

    /**
     * {@inheritdoc}
     */
    public function canBeShown(AcEvent $event)
    {
        return $event->canBeReleased() || \ACEvent::currentUserInEvent($event);
    }

    /**
     * {@inheritdoc}
     */
    public function currentUserInEvent(AcEvent $event)
    {
        if (\Auth::check() && \Auth::user()->driver) {
            return in_array(\Auth::user()->driver->id, \ACEvent::getDriverIDs($event));
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverIDs(AcEvent $event)
    {
        if (!isset($this->driverIDs[$event->id])) {
            $this->driverIDs[$event->id] = \DB::table('ac_championship_entrants')
                ->join('ac_session_entrants', 'ac_championship_entrants.id', '=', 'ac_session_entrants.ac_championship_entrant_id')
                ->join('ac_sessions', 'ac_session_entrants.ac_session_id', '=', 'ac_sessions.id')
                ->select('ac_championship_entrants.driver_id')
                ->where('ac_sessions.ac_event_id', '=', $event->id)
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
        foreach(AcEvent::with('sessions.playlist', 'championship')->get() AS $event) {
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
            $views[$date] = \View::make('assetto-corsa.event.news.results.past', ['events' => $events])->render();
        }
        return $views;
    }
    
    public function getUpcomingNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(AcEvent::with('sessions', 'championship')->get() AS $event) {
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
            $views[$date] = \View::make('assetto-corsa.event.news.results.upcoming', ['events' => $events])->render();
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
            $views[$date] = \View::make('assetto-corsa.event.news.dates', ['events' => $events])->render();
        }
        return $views;
    }
}
