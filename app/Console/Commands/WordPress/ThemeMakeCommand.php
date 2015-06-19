<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ThemeMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:theme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make wordpress theme.';

    /**
     * @var \App\Console\Commands\WordPress\Storage
     */
    protected $storage;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->storage = new Storage('wordpress/wp-content/themes');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $theme_name = $this->argument('name');

        //
        $this->storage->directory($theme_name, function ($storage) {
            $storage->file('index.php')->string('<?php');
            $storage->file('style.css')->template('theme-stubs/style.css', [
                'theme_name' => 'New Theme',
            ]);
            $storage->file('screenshot.png')->string();

            $storage->file('layout.blade.php')->template('theme-stubs/layout.blade.php');
            $storage->file('index.blade.php')->template('theme-stubs/index.blade.php');
        });
        $this->info('success');
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Theme name'],
        ];
    }
}
