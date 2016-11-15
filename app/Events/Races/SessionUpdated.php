<?php

namespace App\Events\Races;

use App\Events\Event;
use App\Models\Races\RacesSession;
use Illuminate\Queue\SerializesModels;

class SessionUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RacesSession
     */
    public $session;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RacesSession $session)
    {
        $this->session = $session;
    }

}
