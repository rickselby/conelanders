<?php

namespace App\Jobs\DirtRally;

use App\Jobs\Job;
use App\Models\DirtRally\Event;
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
        \DirtRallyImportDirt::getEvent($this->event);
    }
}
