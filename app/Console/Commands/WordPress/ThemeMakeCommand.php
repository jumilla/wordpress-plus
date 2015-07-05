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

        // TODO ディレクトリ存在チェック

        $metadata = [];
        $metadata['theme_name'] = trim($this->ask('Theme Name', $theme_name));
        $metadata['theme_uri'] = trim($this->ask('Theme URI', ' '));
        $metadata['author'] = trim($this->ask('Author Name', ' '));
        $metadata['author_uri'] = trim($this->ask('Author URI', ' '));
        $metadata['description'] = trim($this->ask('Description', ' '));
        $metadata['version'] = trim($this->ask('Version', '1.0'));
        $metadata['license'] = trim($this->ask('License', ' '));
        $metadata['license_uri'] = trim($this->ask('License URI', ' '));
        $metadata['tags'] = implode(', ', array_map(function ($value) { return trim($value); }, explode(',', $this->ask('Tags', ' '))));

        $this->storage->directory($theme_name, function ($storage) use ($metadata) {
            $storage->file('index.php')->string('<?php');
            $storage->file('style.css')->template('theme-stubs/style.css', $metadata);
            $storage->file('screenshot.png')->string();

            $storage->file('functions.php')->template('theme-stubs/functions.php');
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

    /**
     * @return array
     */
    protected function getOption()
    {
        return [
        ];
    }
}
