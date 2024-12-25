<?php

namespace App\Console;

use App\Jobs\UpdateMemberStatusJob;
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
        // $schedule->command('app:update-late-return-status')->cron('0 0 * * *');
        $schedule->command('app:update-late-return-status')->everyMinute();
        // $schedule->job(new UpdateMemberStatusJob())->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
