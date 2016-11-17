<?php

namespace App\Listeners\DirtRally;

use App\Events\DirtRally\EventImport;
use App\Models\DirtRally\DirtEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class Events implements ShouldQueue
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            EventImport::class,
            'App\Listeners\DirtRally\Events@importEvent'
        );
    }

    public function importEvent(EventImport $event)
    {
        \DirtRallyImportDirt::importEventDetails($event->event);
    }

}