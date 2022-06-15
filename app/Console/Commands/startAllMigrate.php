<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class startAllMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'startMigrate:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For run all migrate with special database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Artisan::call('migrate --path=/database/migrations/2014_10_12_000000_create_users_table.php');
        \Artisan::call('migrate --path=/database/migrations/2014_10_12_100000_create_password_resets_table.php');
        \Artisan::call('migrate --path=/database/migrations/2019_08_19_000000_create_failed_jobs_table.php');
        \Artisan::call('migrate --path=/database/migrations/2019_12_14_000001_create_personal_access_tokens_table.php');
        \Artisan::call('migrate --path=/database/migrations/2022_06_04_084910_create_unit_table.php');
        \Artisan::call('migrate --path=/database/migrations/2022_06_07_110720_create_alert_table.php --database=beer');
        \Artisan::call('migrate --path=/database/migrations/2022_06_09_143131_create_email_to_send_alert_table.php --database=beer');
        echo "Run all migrate successful";
        return;
    }
}
