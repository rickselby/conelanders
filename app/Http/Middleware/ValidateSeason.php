<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ValidateSeason
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
        if ($params['season']->championship->id == $params['championship'])
        {
            return $next($request);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
