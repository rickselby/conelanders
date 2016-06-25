<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcEvent;

class Event
{

    protected $driverIDs = [];

    /**
     * Can we show the given event's results to the current user?
     *
     * @param AcEvent $event
     *
     * @return bool
     */
    public function canBeShown(AcEvent $event)
    {
        return $this->currentUserInEvent($event) || $event->canBeReleased();
    }

    /**
     * Check if the currently logged in user is part of the given event
     *
     * @param AcEvent $event
     *
     * @return bool
     */
    public function currentUserInEvent(AcEvent $event)
    {
        if (\Auth::check() && \Auth::user()->driver) {
            return in_array(\Auth::user()->driver->id, $this->getDriverIDs($event));
        } else {
            return false;
        }
    }

    /**
     * Get a list of driver IDs that are listed against a session for the given event
     *
     * @param AcEvent $event
     *
     * @return array
     */
    private function getDriverIDs(AcEvent $event)
    {
        # I will/may eventually use proper caching for this, and other things. That's a bigger project though.
        if (!isset($this->driverIDs[$event->id])) {
            $this->driverIDs[$event->id] = \DB::table('drivers')
                ->join('ac_championship_entrants', 'drivers.id', '=', 'ac_championship_entrants.driver_id')
                ->join('ac_session_entrants', 'ac_championship_entrants.id', '=', 'ac_session_entrants.ac_championship_entrant_id')
                ->join('ac_sessions', 'ac_session_entrants.ac_session_id', '=', 'ac_sessions.id')
                ->select('drivers.id')
                ->where('ac_sessions.ac_event_id', '=', $event->id)
                ->pluck('id');
        }

        return $this->driverIDs[$event->id];
    }
}
