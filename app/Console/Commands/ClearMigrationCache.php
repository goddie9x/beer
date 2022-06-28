<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearMigrationCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:cache-opt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear migration cache';

    /**
     * Execute the console command.
     *
     * @return int
     */
     protected $commands = [
    Commands\UpdateEnv::class
    ];
    public function handle()
    {
         \Artisan::call('clear-compiled');
         \Artisan::call('optimize');
        echo "clear migration cache success";
        return;
    }
}
