<?php

namespace App\Events\DirtRally;

use App\Events\Event;
use App\Models\DirtRally\DirtSeason;
use Illuminate\Queue\SerializesModels;

class SeasonUpdated extends Event
{
    use SerializesModels;

    /**
     * @var DirtSeason
     */
    public $season;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DirtSeason $season)
    {
        $this->season = $season;
    }
}
