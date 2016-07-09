<?php

namespace App\Events;

use App\Models\Driver;
use Illuminate\Queue\SerializesModels;

class DriverUpdated extends Event
{
    use SerializesModels;

    /**
     * @var Driver
     */
    public $driver;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

}
