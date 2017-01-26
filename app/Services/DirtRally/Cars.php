<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtCar;
use App\Models\DirtRally\DirtEvent;
use App\Models\Driver;

class Cars
{
    public function updateForEvent(DirtEvent $event, Driver $driver, DirtCar $car)
    {
        foreach($event->stages AS $stage) {
            $result = $stage->results->where('driver_id', $driver->id)->first();
            if ($result) {
                $result->car()->associate($car);
                $result->save();
            }
        }
    }
}
