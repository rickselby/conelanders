<?php

namespace App\Events\News;

use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;

class RequestUpcoming extends Event
{
    use SerializesModels;

    /**
     * @var Carbon
     */
    public $start;

    /**
     * @var Carbon
     */
    public $end;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

}
