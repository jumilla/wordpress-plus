<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades;
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
     * Include the default Artisan commands.
     *
     * @var bool
     */
    protected $includeDefaultCommands = false;

    /**
     * Create a new console kernel instance.
     *
     * @param  \Laravel\Lumen\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        if (!$this->includeDefaultCommands) {
            // setup facade
            $this->app->withFacades();

            // add artisan command 'serve' and 'schedule:run'
            $this->commands = array_merge($this->commands, [
                \Illuminate\Console\Scheduling\ScheduleRunCommand::class,
                \Laravel\Lumen\Console\Commands\ServeCommand::class,
            ]);

            // add artisan command 'cache:*'
            $this->app->make('cache');

            // add artisan command 'queue:*'
            //$this->app->make('queue');

            $this->app->configure('database');
        }
    }

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
