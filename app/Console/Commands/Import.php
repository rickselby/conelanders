<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'results:import';

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
        \ImportDirt::queueEventJobs();
    }
}
