<?php

namespace App\Jobs\AssettoCorsa;

use App\Jobs\Job;
use App\Models\AssettoCorsa\AcSession;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportResultsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /** @var AcSession */
    protected $session;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AcSession $session)
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
        \ACImport::saveResults($this->session);
    }
}
