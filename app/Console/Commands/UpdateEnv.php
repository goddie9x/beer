<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update config env';

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
         \Artisan::call('config:cache');
         \Artisan::call('config:clear');
         \Artisan::call('cache:clear');
        echo "update env success";
        return;
    }
}
