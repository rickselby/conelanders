<?php

namespace App\Events\Races;

use App\Events\Event;
use App\Models\Races\RacesEventSignup;
use Illuminate\Queue\SerializesModels;

class EventSignupUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RacesEventSignup
     */
    public $signup;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RacesEventSignup $signup)
    {
        $this->signup = $signup;
    }

}
