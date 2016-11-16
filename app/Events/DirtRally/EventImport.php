<?php

namespace App\Events\DirtRally;

use App\Events\Event;
use App\Models\DirtRally\DirtEvent;
use Illuminate\Queue\SerializesModels;

class EventImport extends Event
{
    use SerializesModels;

    /**
     * @var DirtEvent
     */
    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DirtEvent $event)
    {
        $this->event = $event;
    }
}
