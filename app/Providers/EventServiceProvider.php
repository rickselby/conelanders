<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        \App\Listeners\AssettoCorsa\News::class,
        \App\Listeners\AssettoCorsa\Playlists::class,
        \App\Listeners\AssettoCorsa\Results::class,
        \App\Listeners\AssettoCorsa\Signups::class,
        \App\Listeners\DirtRally\News::class,
        \App\Listeners\DirtRally\Playlists::class,
        \App\Listeners\DirtRally\Results::class,
    ];
}
