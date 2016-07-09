<?php

namespace App\Services\AssettoCorsa;

use App\Interfaces\AssettoCorsa\EventInterface;
use App\Models\AssettoCorsa\AcEvent;

class Event implements EventInterface
{

    protected $driverIDs = [];

    /**
     * {@inheritdoc}
     */
    public function canBeShown(AcEvent $event)
    {
        return \ACEvent::currentUserInEvent($event) || $event->canBeReleased();
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
}
