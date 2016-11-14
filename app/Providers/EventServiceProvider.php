<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        \App\Listeners\Races\News::class,
        \App\Listeners\Races\Playlists::class,
        \App\Listeners\Races\Results::class,
        \App\Listeners\Races\Signups::class,
        \App\Listeners\DirtRally\News::class,
        \App\Listeners\DirtRally\Playlists::class,
        \App\Listeners\DirtRally\Results::class,
    ];
}
