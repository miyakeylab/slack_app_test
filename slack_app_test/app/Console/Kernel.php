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
        Commands\DailyEmojiCount::class,
        Commands\DailyEmojiUserCount::class,
        Commands\WeeklyEmojiCount::class,
        Commands\WeeklyEmojiUserCount::class,
        Commands\MonthlyEmojiRankingBefore::class,
        Commands\MonthlyEmojiCount::class,
        Commands\MonthlyEmojiUserCount::class,
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('DailyEmojiCount')->dailyAt('09:00');
        $schedule->command('DailyEmojiUserCount')->dailyAt('09:00');
        $schedule->command('WeeklyEmojiCount')->weeklyOn(1, '9:05');
        $schedule->command('WeeklyEmojiUserCount')->weeklyOn(1, '9:05');
        $schedule->command('MonthlyEmojiRankingBefore')->monthlyOn(1, '9:09');
        $schedule->command('MonthlyEmojiCount')->monthlyOn(1, '9:10');
        $schedule->command('MonthlyEmojiUserCount')->monthlyOn(1, '9:10');
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
