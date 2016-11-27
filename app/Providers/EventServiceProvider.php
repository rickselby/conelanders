<?php

namespace App\Providers;

use App\Listeners\DirtRally\Events;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        \App\Listeners\Races\Championships::class,
        \App\Listeners\Races\Categories::class,
        \App\Listeners\Races\News::class,
        \App\Listeners\Races\Playlists::class,
        \App\Listeners\Races\Results::class,
        \App\Listeners\Races\Signups::class,
        \App\Listeners\DirtRally\Events::class,
        \App\Listeners\DirtRally\News::class,
        \App\Listeners\DirtRally\Playlists::class,
        \App\Listeners\DirtRally\Results::class,
        \App\Listeners\RallyCross\Championships::class,
        \App\Listeners\RallyCross\News::class,
        \App\Listeners\RallyCross\Results::class,
    ];
}
