<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportEventJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /** @var Event */
    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \ImportDirt::getEvent($this->event);
    }
}
