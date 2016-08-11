<?php

namespace App\Services\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcSession;
use Symfony\Component\HttpFoundation\Request;

class Entrants
{
    public function updateSessionEntrants(Request $request, AcSession $session)
    {
        foreach($session->entrants AS $entrant) {
            $entrant->car = $request->get('car')[$entrant->id];
            if ($session->type == AcSession::TYPE_RACE) {
                $entrant->dsq = isset($request->get('dsq')[$entrant->id]);
                $entrant->dnf = isset($request->get('dnf')[$entrant->id]);
            }
            $entrant->save();
        }
    }

    public function updateNumbersAndCSS(Request $request, AcChampionship $championship)
    {
        foreach($championship->entrants AS $entrant) {
            $entrant->number = $request->get('number')[$entrant->id];
            $entrant->css = $request->get('css')[$entrant->id];
            $entrant->rookie = isset($request->get('rookie')[$entrant->id]);
            $entrant->save();
        }
    }

}