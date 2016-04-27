<?php

namespace App\Console\Commands\DirtRally;

use App\Models\DirtRally\Event;
use App\Models\DirtRally\Stage;
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
        foreach(Stage::all() AS $stage) {
            \Positions::updateStagePositions($stage);
        }
        foreach(Event::all() AS $event) {
            \Positions::updateEventPositions($event);
        }

    }
}
