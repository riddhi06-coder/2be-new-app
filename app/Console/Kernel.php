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
        /*
        |--------------------------------------------------------------------------
        | Monthly Pumping Report Reminder
        |--------------------------------------------------------------------------
        | This runs every day at 9:00 AM.
        | The command itself checks if today is the last day of the month.
        */

        // $schedule->command('email:monthly-report-reminder')
        //          ->everyminute();
        
        }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}