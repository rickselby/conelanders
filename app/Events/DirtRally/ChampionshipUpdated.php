<?php

namespace App\Events\DirtRally;

use App\Events\Event;
use App\Models\DirtRally\DirtChampionship;
use Illuminate\Queue\SerializesModels;

class ChampionshipUpdated extends Event
{
    use SerializesModels;

    /**
     * @var DirtChampionship
     */
    public $championship;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DirtChampionship $championship)
    {
        $this->championship = $championship;
    }
}
