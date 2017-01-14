<?php

namespace App\Services\Races;

use App\Http\Requests\Races\PenaltyRequest;
use App\Models\Races\RacesPenalty;
use App\Models\Races\RacesSession;

class Penalties
{
    /**
     * Add a penalty to an entrant
     *
     * @param PenaltyRequest $request
     * @param RacesSession $session
     *
     * @return bool
     */
    public function add(PenaltyRequest $request, RacesSession $session)
    {
        $entrant = $session->entrants()->find($request->get('entrant'));
        if ($entrant) {
            $penalty = new RacesPenalty(
                $request->only(['points', 'reason'])
            );
            $entrant->penalties()->save($penalty);
            return true;
        } else {
            return false;
        }
    }

    public function forSession(RacesSession $session)
    {

    }
}
