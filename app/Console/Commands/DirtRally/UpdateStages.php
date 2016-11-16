<?php

namespace App\Console\Commands\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtStage;
use Illuminate\Console\Command;

class UpdateStages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stages:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stage information from the dirt website';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \DirtRallyImportDirt::updateAllStages();
    }
}
