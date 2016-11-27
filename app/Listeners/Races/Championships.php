<?php

namespace App\Listeners\Races;

use App\Events\UserChampionships;
use Illuminate\Events\Dispatcher;

class Championships
{
    /**
     * @var \App\Services\Races\Championships
     */
    protected $champService;

    public function __construct(\App\Services\Races\Championships $champService)
    {
        $this->champService = $champService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserChampionships::class,
            'App\Listeners\Races\Championships@getUserChampionships'
        );
    }

    public function getUserChampionships(UserChampionships $event)
    {
        return $this->champService->getUserChampionships();
    }


}