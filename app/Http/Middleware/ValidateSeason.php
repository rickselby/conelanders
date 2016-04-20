<?php

namespace App\Http\Middleware;

class ValidateSeason
{
    use ValidateChain;

    public function handle($request, $next)
    {
        $request->attributes->add(['season' => $this->validateSeason($request)]);
        return $next($request);
    }
}
