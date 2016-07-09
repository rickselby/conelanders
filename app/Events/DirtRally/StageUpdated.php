<?php

namespace App\Events\DirtRally;

use App\Events\Event;
use App\Models\DirtRally\DirtStage;
use Illuminate\Queue\SerializesModels;

class StageUpdated extends Event
{
    use SerializesModels;

    /**
     * @var DirtStage
     */
    public $stage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DirtStage $stage)
    {
        $this->stage = $stage;
    }
}
