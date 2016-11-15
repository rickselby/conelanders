<?php

namespace App\Events\RallyCross;

use App\Events\Event;
use App\Models\RallyCross\RxCar;
use Illuminate\Queue\SerializesModels;

class CarUpdated extends Event
{
    use SerializesModels;

    /**
     * @var RxCar
     */
    public $car;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RxCar $car)
    {
        $this->car = $car;
    }

}
