<?php

namespace App\Http\Middleware;

class ValidateStage
{
    use ValidateChain;

    public function handle($request, $next)
    {
        $request->attributes->add(['stage' => $this->validateStage($request)]);
        return $next($request);
    }
}
