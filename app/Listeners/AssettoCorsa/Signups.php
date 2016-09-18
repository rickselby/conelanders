<?php

namespace App\Listeners\AssettoCorsa;

use App\Events\AssettoCorsa\EventSignupUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class Signups implements ShouldQueue
{
    /** @var \App\Services\AssettoCorsa\Signups */
    protected $signupsService;

    public function __construct(\App\Services\AssettoCorsa\Signups $signupsService)
    {
        $this->signupsService = $signupsService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            EventSignupUpdated::class,
            'App\Listeners\AssettoCorsa\Signups@updateSignup'
        );
    }

    public function updateSignup(EventSignupUpdated $event)
    {
        $this->signupsService->updateSignup($event->signup);
    }
}