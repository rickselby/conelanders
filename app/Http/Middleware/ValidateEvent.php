<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ValidateEvent
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
        $params = $request->route()->parameters();
        if ($params['event']->season->id == $params['season'])
        {
            return $next($request);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
