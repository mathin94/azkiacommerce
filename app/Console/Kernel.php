<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('app:sync-customer')->daily();

        $schedule->command('backup:clean')->dailyAt('00:00');
        $schedule->command('backup:run --only-db')->dailyAt('12:00');
        $schedule->command('backup:run --only-db')->dailyAt('17:00');
        $schedule->command('backup:run --only-db')->dailyAt('21:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('.psysh.config.php');

        require base_path('routes/console.php');
    }
}
