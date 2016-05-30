<?php

namespace App\Http\Middleware\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcEvent;
use App\Models\AssettoCorsa\AcSession;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ValidateChain
{
    /**
     * @param $request
     * @return AcChampionship
     */
    protected function validateChampionship($request)
    {
        $championship = AcChampionship::findBySlug($request->route('championship'));
        $this->varExists($championship);
        return $championship;
    }

    /**
     * @param $request
     * @return AcEvent
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
     * @return AcSession
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