<?php

namespace App\Services\RallyCross;

use App\Interfaces\RallyCross\EventInterface;
use App\Models\PointsSequence;
use App\Models\RallyCross\RxEvent;
use Carbon\Carbon;

class Event implements EventInterface
{
    protected $driverIDs = [];

    /**
     * {@inheritdoc}
     */
    public function canBeShown(RxEvent $event)
    {
        return $event->canBeReleased() || \RXEvent::currentUserInEvent($event);
    }

    /**
     * {@inheritdoc}
     */
    public function currentUserInEvent(RxEvent $event)
    {
        if (\Auth::check() && \Auth::user()->driver) {
            return in_array(\Auth::user()->driver->id, \RXEvent::getDriverIDs($event));
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDriverIDs(RxEvent $event)
    {
        if (!isset($this->driverIDs[$event->id])) {
            $this->driverIDs[$event->id] = \DB::table('rx_session_entrants')
                ->join('rx_sessions', 'rx_session_entrants.rx_session_id', '=', 'rx_sessions.id')
                ->join('rx_event_entrants', 'rx_event_entrants.id', '=', 'rx_session_entrants.rx_event_entrant_id')
                ->select('rx_event_entrants.driver_id')
                ->where('rx_sessions.rx_event_id', '=', $event->id)
                ->distinct()->pluck('driver_id');
        }

        return $this->driverIDs[$event->id];
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeatResults(RxEvent $event)
    {
        return $event->heatResult->count();
    }

    /**
     * {@inheritdoc}
     */
    public function areHeatsComplete(RxEvent $event)
    {
        if (!$event->heats->count()) {
            return false;
        }
        foreach($event->heats AS $session) {
            if (!$session->show) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeatPoints(RxEvent $event)
    {
        return $event->heatResult->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeatResults(RxEvent $event)
    {
        $results = [];
        foreach($event->heats AS $session) {
            foreach($session->entrants AS $entrant) {
                if (!isset($results[$entrant->eventEntrant->id])) {
                    $results[$entrant->eventEntrant->id] = [
                        'entrant' => $entrant->eventEntrant,
                        'heatPoints' => 0,
                        'positions' => [],
                        'points' => 0,
                    ];
                }
                $results[$entrant->eventEntrant->id]['positions'][] = $entrant->position;
                $results[$entrant->eventEntrant->id]['heatPoints'] += $entrant->points;
            }
        }

        if ($this->hasHeatResults($event)) {
            foreach($event->heatResult AS $heatResult) {
                $results[$heatResult->entrant->id]['points'] = $heatResult->points;
            }
        }

        usort($results, [$this, 'sortHeatResults']);

        return \Positions::addToArray($results, [$this, 'areHeatResultsEqual']);
    }

    /**
     * Sort heat results
     *
     * @param $a
     * @param $b
     * @return int
     */
    private function sortHeatResults($a, $b)
    {
        if ($a['heatPoints'] != $b['heatPoints']) {
            return $b['heatPoints'] - $a['heatPoints'];
        }

        $positions = [
            'a' => array_values($a['positions']),
            'b' => array_values($b['positions']),
        ];
        sort($positions['a']);
        sort($positions['b']);

        // Then, best finishing positions; all the way down...
        for($i = 0; $i < max(count($positions['a']), count($positions['b'])); $i++) {
            // Check both have a position set
            if (isset($positions['a'][$i]) && isset($positions['b'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($positions['a'][$i] != $positions['b'][$i]) {
                    return $positions['a'][$i] - $positions['b'][$i];
                }
            } elseif (isset($positions['a'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($positions['b'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        return 0;
    }

    /**
     * Check if two heat results are identical
     * @param $a
     * @param $b
     * @return bool
     */
    public function areHeatResultsEqual($a, $b)
    {
        return ($a['heatPoints'] == $b['heatPoints'])
            && ($a['positions'] == $b['positions']);
    }

    /**
     * {@inheritdoc}
     */
    public function applyHeatsPointsSequence(RxEvent $event, PointsSequence $sequence)
    {
        $points = \PointSequences::get($sequence);
        foreach($this->getHeatResults($event) AS $entrant) {
            $this->setHeatsPointsFor($event, $entrant, isset($points[$entrant['position']]) ? $points[$entrant['position']] : 0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setHeatsPoints(RxEvent $event, $points)
    {
        foreach($this->getHeatResults($event) AS $entrant) {
            $this->setHeatsPointsFor($event, $entrant, $points[$entrant['driver']->id]);
        }
    }

    /**
     * Set points for an entrant, if they can have points...
     *
     * @param RxEvent $event
     * @param [] $entrant
     * @param int|NULL $points
     */
    private function setHeatsPointsFor(RxEvent $event, $entrant, $points)
    {
        $heatResult = $event->heatResult()->firstOrNew([
            'rx_event_entrant_id' => $entrant['entrant']->id,
        ]);
        $heatResult->fill([
            'position' => $entrant['position'],
            'points' => $points !== NULL ? $points : 0,
        ])->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getPastNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(RxEvent::with('sessions', 'championship')->get() AS $event) {
            if ($event->release && $event->release->between($start, $end)) {
                if (!isset($news[$event->release->timestamp])) {
                    $news[$event->release->timestamp] = [];
                }

                $news[$event->release->timestamp][] = $event;
            }
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('rallycross.event.news.results.past', ['events' => $events])->render();
        }
        return $views;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpcomingNews(Carbon $start, Carbon $end)
    {
        $news = [];
        foreach(RxEvent::with('sessions', 'championship')->get() AS $event) {
            if ($event->release && $event->release->between($start, $end)) {
                if (!isset($news[$event->release->timestamp])) {
                    $news[$event->release->timestamp] = [];
                }

                $news[$event->release->timestamp][] = $event;
            }
        }
        $views = [];
        foreach($news AS $date => $events) {
            $views[$date] = \View::make('rallycross.event.news.results.upcoming', ['events' => $events])->render();
        }
        return $views;
    }

}