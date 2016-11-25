<?php

namespace App\Listeners\RallyCross;

use App\Events\UserChampionships;
use Illuminate\Events\Dispatcher;

class Championships
{
    /**
     * @var \App\Services\RallyCross\Championships
     */
    protected $champService;

    public function __construct(\App\Services\RallyCross\Championships $champService)
    {
        $this->champService = $champService;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            UserChampionships::class,
            'App\Listeners\RallyCross\Championships@getUserChampionships'
        );
    }

    public function getUserChampionships(UserChampionships $event)
    {
        return $this->champService->getUserChampionships();
    }


}