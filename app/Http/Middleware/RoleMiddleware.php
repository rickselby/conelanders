<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (\Auth::guest()) {
            return redirect()->guest('login');
        }

        if (! $request->user()->can($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
