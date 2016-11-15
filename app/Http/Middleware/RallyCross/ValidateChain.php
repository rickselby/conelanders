<?php

namespace App\Http\Middleware\RallyCross;

use App\Models\Races\RacesChampionship;
use App\Models\Races\RacesEvent;
use App\Models\Races\RacesSession;
use App\Models\RallyCross\RxChampionship;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ValidateChain
{
    /**
     * @param $request
     * @return RxChampionship
     */
    protected function validateChampionship($request)
    {
        $championship = RxChampionship::findBySlug($request->route('championship'));
        $this->varExists($championship);
        return $championship;
    }

    /**
     * @param $request
     * @return RacesEvent
     */
    protected function validateEvent($request)
    {
        $championship = $this->validateChampionship($request);
        $event = $championship->events()->where('slug', $request->route('event'))->first();
        $this->varExists($event);
        return $event;
    }

    /**
     * @param $request
     * @return RacesSession
     */
    protected function validateSession($request)
    {
        $event = $this->validateEvent($request);
        $session = $event->sessions()->where('slug', $request->route('session'))->first();
        $this->varExists($session);
        return $session;
    }

    protected function varExists($var)
    {
        if (!$var|| !$var->exists) {
            throw new NotFoundHttpException();
        }
    }
}