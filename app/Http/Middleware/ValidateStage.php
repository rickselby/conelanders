<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ValidateStage
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
        if ($params['stage']->event->id == $params['event'] && $params['stage']->event->season->id == $params['season'])
        {
            return $next($request);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
