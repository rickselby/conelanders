<?php

namespace App\Http\Middleware\Races;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ValidateTeam
{
    public function handle($request, $next)
    {
        if ($request->route('team')->championship != $request->route('championship')) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}
