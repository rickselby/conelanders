<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        if (\Auth::check() && \Auth::user()->admin)
        {
            return $next($request);
        } else {
            return response('Unauthorized.', 401);
        }
    }
}
