<?php

namespace App\Services\Races;

use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesSession;
use Symfony\Component\HttpFoundation\Request;

class Entrants
{
    public function updateSessionEntrants(Request $request, RacesSession $session)
    {
        foreach($session->entrants AS $entrant) {
            if ($session->type == RacesSession::TYPE_RACE) {
                $entrant->dsq = isset($request->get('dsq')[$entrant->id]);
                $entrant->dnf = isset($request->get('dnf')[$entrant->id]);
            }
            $entrant->save();
        }
    }

    public function updateEntrantDetails(Request $request, RacesChampionship $championship)
    {
        foreach($championship->entrants AS $entrant) {
            $entrant->number = $request->get('number')[$entrant->id];
            $entrant->css = $request->get('css')[$entrant->id];
            $entrant->colour = $request->get('colour')[$entrant->id];
            $entrant->colour2 = $request->get('colour2')[$entrant->id];
            $entrant->rookie = isset($request->get('rookie')[$entrant->id]);
            $entrant->save();
        }
    }

}