<?php

namespace App\Events\RallyCross;

use App\Events\Event;
use App\Models\RallyCross\RxEvent;
use App\Models\RallyCross\RxSession;
use Illuminate\Queue\SerializesModels;

class SessionUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RxSession
     */
    public $session;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RxSession $session)
    {
        $this->session = $session;
    }

}
