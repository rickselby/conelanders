<?php

namespace App\Events\AcHotlap;

use App\Events\Event;
use App\Models\AcHotlap\AcHotlapSession;
use Illuminate\Queue\SerializesModels;

class SessionUpdated extends Event
{
    use SerializesModels;

    /**
     * @var AcHotlapSession
     */
    public $session;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AcHotlapSession $session)
    {
        $this->session = $session;
    }

}
