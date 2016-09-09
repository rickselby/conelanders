<?php

namespace App\Events\AssettoCorsa;

use App\Events\Event;
use App\Models\AssettoCorsa\AcCar;
use App\Models\AssettoCorsa\AcChampionship;
use Illuminate\Queue\SerializesModels;

class CarUpdated extends Event
{
    use SerializesModels;

    /**
     * @var AcCar
     */
    public $car;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AcCar $car)
    {
        $this->car = $car;
    }

}
