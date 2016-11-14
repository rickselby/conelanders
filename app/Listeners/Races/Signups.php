<?php

namespace App\Listeners\Races;

use App\Events\Races\EventSignupUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class Signups implements ShouldQueue
{
    /** @var \App\Services\Races\Signups */
    protected $signupsService;

    public function __construct(\App\Services\Races\Signups $signupsService)
    {
        $this->signupsService = $signupsService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            EventSignupUpdated::class,
            'App\Listeners\Races\Signups@updateSignup'
        );
    }

    public function updateSignup(EventSignupUpdated $event)
    {
        $this->signupsService->updateSignup($event->signup);
    }
}