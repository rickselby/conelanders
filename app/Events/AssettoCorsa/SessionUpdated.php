<?php

namespace App\Events\AssettoCorsa;

use App\Events\Event;
use App\Models\AssettoCorsa\AcSession;
use Illuminate\Queue\SerializesModels;

class SessionUpdated extends Event
{
    use SerializesModels;

    /**
     * @var AcSession
     */
    public $session;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AcSession $session)
    {
        $this->session = $session;
    }

}
