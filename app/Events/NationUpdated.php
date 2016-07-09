<?php

namespace App\Events;

use App\Models\Nation;
use Illuminate\Queue\SerializesModels;

class NationUpdated extends Event
{
    use SerializesModels;

    /**
     * @var Nation
     */
    public $nation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Nation $nation)
    {
        $this->nation = $nation;
    }

}
