<?php

namespace App\Http\Middleware\Races;

class ValidateSession
{
    use ValidateChain;

    public function handle($request, $next)
    {
        $request->attributes->add(['session' => $this->validateSession($request)]);
        return $next($request);
    }
}
