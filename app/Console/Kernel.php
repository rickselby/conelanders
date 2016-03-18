<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Import::class,
        Commands\CSV::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Import results every 3 hours
        $schedule->command('results:import')->cron('0 */3 * * *');

        // Check for a last pull every minute (ouch)
        $schedule->call(function() {
            \ImportDirt::queueLastImport();
        });
    }
}
