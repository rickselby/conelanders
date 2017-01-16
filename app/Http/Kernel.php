<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Krucas\Notification\Middleware\NotificationMiddleware::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        'role' => \App\Http\Middleware\RoleMiddleware::class,

        'dirt-rally.validateSeason' => \App\Http\Middleware\DirtRally\ValidateSeason::class,
        'dirt-rally.validateEvent' => \App\Http\Middleware\DirtRally\ValidateEvent::class,
        'dirt-rally.validateStage' => \App\Http\Middleware\DirtRally\ValidateStage::class,

        'races.validateEntrant' => \App\Http\Middleware\Races\ValidateEntrant::class,
        'races.validateEvent' => \App\Http\Middleware\Races\ValidateEvent::class,
        'races.validateSession' => \App\Http\Middleware\Races\ValidateSession::class,
        'races.validateTeam' => \App\Http\Middleware\Races\ValidateTeam::class,

        'rallycross.validateEvent' => \App\Http\Middleware\RallyCross\ValidateEvent::class,
        'rallycross.validateSession' => \App\Http\Middleware\RallyCross\ValidateSession::class,
    ];
}
