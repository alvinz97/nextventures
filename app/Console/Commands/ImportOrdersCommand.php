<?php

namespace App\Console\Commands;

use Domain\Order\Jobs\ProcessOrderJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use League\Csv\Reader;

class ImportOrdersCommand extends Command
{
    protected $signature = 'orders:import {file}';
    protected $description = 'Import orders from CSV and queue processing';

    public function handle(): void
    {
        $path = public_path('import_templates/' . $this->argument('file'));
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            Bus::dispatch(new ProcessOrderJob($record));
        }

        $this->info('Orders queued for processing!');
    }
}
