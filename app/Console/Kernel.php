<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        //This is the line of code added, at the end, we the have class name of DeleteInActiveUsers.php inside app\console\commands
        '\App\Console\Commands\AddAllProduct',
        '\App\Console\Commands\RefreshMonitor',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('addall:product')
//            ->monthly();

//        $schedule->command('addall:product')->monthly()->when(function () {
//
//            return true;
//        });

//        $schedule->command('updatesellerquantity:sellerquantity')
//            ->hourly();

        $schedule->command('monitor:refreshproduct')
//            ->hourly();
            ->cron('* */6 * * * *'); // every 6 hours
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
