<?php

namespace App\Console\Commands\DirtRally;

use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dirt:results-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from the dirtgame.com website';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \DirtRallyImportDirt::queueEventJobs();
    }

}
