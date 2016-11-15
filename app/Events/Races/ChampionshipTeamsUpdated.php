<?php

namespace App\Events\Races;

use App\Events\Event;
use App\Models\Races\RacesChampionship;
use Illuminate\Queue\SerializesModels;

class ChampionshipTeamsUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RacesChampionship
     */
    public $championship;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RacesChampionship $championship)
    {
        $this->championship = $championship;
    }

}
