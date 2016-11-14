<?php

namespace App\Jobs\Races;

use App\Jobs\Job;
use App\Models\Races\RacesSession;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportResultsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /** @var RacesSession */
    protected $session;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RacesSession $session)
    {
        $this->session = $session;
        $session->importing = true;
        $session->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \RacesImport::saveResults($this->session);
    }
}
