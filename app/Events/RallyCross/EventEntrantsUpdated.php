<?php

namespace App\Events\RallyCross;

use App\Events\Event;
use App\Models\RallyCross\RxEvent;
use Illuminate\Queue\SerializesModels;

class EventEntrantsUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RxEvent
     */
    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RxEvent $event)
    {
        $this->event = $event;
    }

}
