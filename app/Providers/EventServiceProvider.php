<?php

namespace App\Providers;

use App\Listeners\AssettoCorsa\Results;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        Results::class,
    ];
}
