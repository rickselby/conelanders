<?php

namespace App\Events\RallyCross;

use App\Events\Event;
use App\Models\RallyCross\RxChampionship;
use Illuminate\Queue\SerializesModels;

class ChampionshipUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RxChampionship
     */
    public $championship;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RxChampionship $championship)
    {
        $this->championship = $championship;
    }

}
