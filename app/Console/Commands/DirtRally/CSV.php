<?php

namespace App\Console\Commands\DirtRally;

use Illuminate\Console\Command;

class CSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dirt:results-csv {event_id} {file}';

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
        $lines = file($this->argument('file'));
        $csv = [];
        foreach($lines AS $line) {
            $csv[] = str_getcsv($line, ',');
        }

        \DirtRallyImportCSV::fromCSV($this->argument('event_id'), $csv);
    }
}
