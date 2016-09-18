<?php

namespace App\Events\AssettoCorsa;

use App\Events\Event;
use App\Models\AssettoCorsa\AcEventSignup;
use Illuminate\Queue\SerializesModels;

class EventSignupUpdated extends Event
{
    use SerializesModels;

    /**
     * @var AcEventSignup
     */
    public $signup;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AcEventSignup $signup)
    {
        $this->signup = $signup;
    }

}
