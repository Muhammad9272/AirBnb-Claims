<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // Pulls claim status / amount / requested-info changes made in Notion
        // back into the website. Requires a server cron entry running
        // `php artisan schedule:run` every minute - see deployment notes.
        if (config('services.notion.enabled')) {
            $schedule->job(new \App\Jobs\PullClaimUpdatesFromNotion())
                ->name('notion-claims-pull')
                ->everyTwoMinutes()
                ->withoutOverlapping(10);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
