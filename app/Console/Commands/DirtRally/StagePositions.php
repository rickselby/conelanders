<?php

namespace App\Console\Commands\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtStage;
use Illuminate\Console\Command;

class StagePositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'positions:stages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate positions for stages and events';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach(DirtStage::all() AS $stage) {
            \DirtRallyPositions::updateStagePositions($stage);
        }
        foreach(DirtEvent::all() AS $event) {
            \DirtRallyPositions::updateEventPositions($event);
        }

    }
}
