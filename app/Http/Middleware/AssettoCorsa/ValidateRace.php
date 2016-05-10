<?php

namespace App\Http\Middleware\AssettoCorsa;

class ValidateRace
{
    use ValidateChain;

    public function handle($request, $next)
    {
        $request->attributes->add(['race' => $this->validateRace($request)]);
        return $next($request);
    }
}
