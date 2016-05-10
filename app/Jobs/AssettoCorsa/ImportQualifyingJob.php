<?php

namespace App\Jobs\AssettoCorsa;

use App\Jobs\Job;
use App\Models\AssettoCorsa\AcRace;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportQualifyingJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /** @var AcRace */
    protected $race;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AcRace $race)
    {
        $this->race = $race;
        $race->qualifying_import = true;
        $race->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \ACImport::saveQualifying($this->race);
    }
}
