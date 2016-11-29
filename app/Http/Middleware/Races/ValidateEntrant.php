<?php

namespace App\Http\Middleware\Races;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ValidateEntrant
{
    public function handle($request, $next)
    {
        if ($request->route('entrant')->championship != $request->route('championship')) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}
