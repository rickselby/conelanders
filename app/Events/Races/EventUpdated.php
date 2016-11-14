<?php

namespace App\Events\Races;

use App\Events\Event;
use App\Models\Races\RacesEvent;
use Illuminate\Queue\SerializesModels;

class EventUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RacesEvent
     */
    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RacesEvent $event)
    {
        $this->event = $event;
    }

}
