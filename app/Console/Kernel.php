<?php

namespace App\Console;

use App\Console\Commands\CalculateEarningCommnad;
use App\Console\Commands\GetBrands;
use App\Console\Commands\GetCompetitorsPrice;
use App\Console\Commands\UpdateCurrecnyCommand;
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
        //
        GetBrands::class,
        GetCompetitorsPrice::class,
        CalculateEarningCommnad::class,
        UpdateCurrecnyCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command('get:brand')->weekly();
        $schedule->command('calculate:earning')->twiceDaily();
        $schedule->command('calculate:earning')->daily();
        $schedule->command('competitors')->daily();
        $schedule->command('send:offers')->daily();
        $schedule->command('send:reminder')->daily();
        $schedule->command('update:currency')->everySixHours();
        $schedule->command('queue:work --stop-when-empty')->everyMinute();
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
}
