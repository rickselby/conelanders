<?php

namespace App\Http\Middleware\AssettoCorsa;

use App\Models\AssettoCorsa\AcChampionship;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ValidateChain
{
    protected function validateChampionship($request)
    {
        $championship = AcChampionship::findBySlug($request->route('championship'));
        $this->varExists($championship);
        return $championship;
    }

    protected function validateRace($request)
    {
        $championship = $this->validateChampionship($request);
        $season = $championship->races()->where('slug', $request->route('race'))->first();
        $this->varExists($season);
        return $season;
    }

    protected function varExists($var)
    {
        if (!$var|| !$var->exists) {
            throw new NotFoundHttpException();
        }
    }
}