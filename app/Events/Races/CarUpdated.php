<?php

namespace App\Events\Races;

use App\Events\Event;
use App\Models\Races\RacesCar;
use App\Models\Races\RacesChampionship;
use Illuminate\Queue\SerializesModels;

class CarUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RacesCar
     */
    public $car;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RacesCar $car)
    {
        $this->car = $car;
    }

}
