<?php

namespace App\Http\Middleware\DirtRally;

class ValidateStage
{
    use ValidateChain;

    public function handle($request, $next)
    {
        $request->attributes->add(['stage' => $this->validateStage($request)]);
        return $next($request);
    }
}
