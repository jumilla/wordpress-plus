<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\WordPress\StatusCommand::class,
        Commands\WordPress\InstallCommand::class,
        Commands\WordPress\UninstallCommand::class,
        Commands\WordPress\MultisiteInstallCommand::class,
        Commands\WordPress\MultisiteUninstallCommand::class,
        Commands\WordPress\ThemeListCommand::class,
        Commands\WordPress\PluginListCommand::class,
        Commands\WordPress\KeysMakeCommand::class,
        Commands\WordPress\ThemeMakeCommand::class,
        Commands\WordPress\PluginMakeCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 5分ごとに wp-cron.php を実行する
        $schedule->call(function () {
            info('Schedule run: wp-cron.php');
            require wordpress_path('wp-cron.php');
        })->everyFiveMinutes();
    }
}
