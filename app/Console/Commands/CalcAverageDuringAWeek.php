<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalcAverageDuringAWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calc-avg:week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculator average values each week';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
