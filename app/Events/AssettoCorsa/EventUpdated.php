<?php

namespace App\Events\AssettoCorsa;

use App\Events\Event;
use App\Models\AssettoCorsa\AcEvent;
use Illuminate\Queue\SerializesModels;

class EventUpdated extends Event
{
    use SerializesModels;

    /**
     * @var AcEvent
     */
    public $event;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AcEvent $event)
    {
        $this->event = $event;
    }

}
