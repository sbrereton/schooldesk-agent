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
        /* Process Monitoring Payloads */
        $schedule->command('agent:monitoring-service')->everyMinute()->withoutOverlapping()->runInBackground();

        /* Check for Kiosk Password Reset Requests */
        $schedule->command('agent:kiosk-service')->everyFiveSeconds()->withoutOverlapping()->runInBackground();

        /* Synchronize Papercut Data and send to tenant */
        $schedule->command('agent:papercut-service')->everyThirtyMinutes()->withoutOverlapping()->runInBackground();

        /* Synchronize Local Users */
        $schedule->command('agent:usersync-service')->everyFiveMinutes()->withoutOverlapping()->runInBackground();

        /* Synchronize EMC Users */
        $schedule->command('agent:edustar-service')->daily()->withoutOverlapping()->runInBackground();

        /* Synchronize CRT Accounts Users */
        $schedule->command('agent:crtaccount-service')->daily()->withoutOverlapping()->runInBackground();

        /* Send Agent Heartbeat */
        $schedule->command('agent:heartbeat')->everyFiveMinutes()->withoutOverlapping()->runInBackground();

        /* Process Command Queues */
        $schedule->command('agent:process-command-queue')->everyMinute()->withoutOverlapping()->runInBackground();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    protected function bootstrappers()
    {
        return array_merge(
            [\Bugsnag\BugsnagLaravel\OomBootstrapper::class],
            parent::bootstrappers(),
        );
    }
}
