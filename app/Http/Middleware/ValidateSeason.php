<?php

namespace App\Http\Middleware;

use Closure;

class ValidateSeason extends ValidateChain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $request->attributes->add(['season' => $this->validateSeason($request)]);
        return $next($request);
    }
}
