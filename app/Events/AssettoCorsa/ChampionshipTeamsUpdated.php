<?php

namespace App\Events\AssettoCorsa;

use App\Events\Event;
use App\Models\AssettoCorsa\AcChampionship;
use Illuminate\Queue\SerializesModels;

class ChampionshipTeamsUpdated extends Event
{
    use SerializesModels;

    /**
     * @var AcChampionship
     */
    public $championship;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AcChampionship $championship)
    {
        $this->championship = $championship;
    }

}
