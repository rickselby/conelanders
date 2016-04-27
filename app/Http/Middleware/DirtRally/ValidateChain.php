<?php

namespace App\Http\Middleware\DirtRally;

use App\Models\DirtRally\Championship;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ValidateChain
{
    protected function validateChampionship($request)
    {
        $championship = Championship::findBySlug($request->route('championship'));
        $this->varExists($championship);
        return $championship;
    }

    protected function validateSeason($request)
    {
        $championship = $this->validateChampionship($request);
        $season = $championship->seasons()->where('slug', $request->route('season'))->first();
        $this->varExists($season);
        return $season;
    }

    protected function validateEvent($request)
    {
        $season = $this->validateSeason($request);
        $event = $season->events()->where('slug', $request->route('event'))->first();
        $this->varExists($event);
        return $event;
    }

    protected function validateStage($request)
    {
        $event = $this->validateEvent($request);
        $stage = $event->stages()->where('slug', $request->route('stage'))->first();
        $this->varExists($stage);
        return $stage;
    }

    protected function varExists($var)
    {
        if (!$var|| !$var->exists) {
            throw new NotFoundHttpException();
        }
    }
}