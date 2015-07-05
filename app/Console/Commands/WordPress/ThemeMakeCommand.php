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
     * @var array
     */
    protected $skeletons = [
        'minimum',
        'simple',
        'bootstrap',
    ];

    /**
     * @var string
     */
    protected $default_skeleton = 'simple';

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

        $skeleton = $this->argument('skeleton');

        if ($skeleton) {
            if (!in_array($skeleton, $this->skeletons)) {
                throw new \InvalidArgumentException("Skeleton '$skeleton' is not found.");
            }
        }
        else {
            $skeleton = $this->choice('Skeleton type', $this->skeletons, array_search($this->default_skeleton, $this->skeletons));
        }

        // TODO ディレクトリ存在チェック

        $metadata = [];
        $metadata['lang'] = env('APP_LOCALE', 'en');
        $metadata['theme_name'] = trim($this->ask('Theme title', $theme_name));
        $metadata['theme_uri'] = trim($this->ask('Theme URI', ' '));
        $metadata['author'] = trim($this->ask('Author name', ' '));
        $metadata['author_uri'] = trim($this->ask('Author URI', ' '));
        $metadata['description'] = trim($this->ask('Description', ' '));
        $metadata['version'] = trim($this->ask('Version', '1.0'));
        $metadata['license'] = trim($this->ask('License', ' '));
        $metadata['license_uri'] = trim($this->ask('License URI', ' '));
        $metadata['tags'] = implode(', ', array_map(function ($value) { return trim($value); }, explode(',', $this->ask('Tags', ' '))));
        $metadata['php_autoload_dir'] = trim($this->ask('PHP autoload directory', 'classes'));
        $metadata['php_namespace'] = trim($this->ask('PHP namespace', ' '));

        $this->{'generateSkeleton' . ucfirst($skeleton)}($theme_name, $metadata, 'theme-stubs/' . $skeleton);

        $this->info('success');
    }

    protected function generateSkeletonMinimum($theme_name, array $metadata, $stub_path)
    {
        $this->storage->directory($theme_name, function ($storage) use ($metadata, $stub_path) {
            $storage->file('index.php')->template($stub_path . '/index.php', $metadata);
            $storage->file('style.css')->template($stub_path . '/style.css', $metadata);
            $storage->file('screenshot.png')->touch(); // TODO use no-image
            $storage->file('functions.php')->template($stub_path . '/functions.php', $metadata);
        });
    }

    protected function generateSkeletonSimple($theme_name, array $metadata, $stub_path)
    {
        $this->storage->directory($theme_name, function ($storage) use ($metadata, $stub_path) {
            $storage->file('index.php')->template($stub_path . '/index.php', $metadata);
            $storage->file('style.css')->template($stub_path . '/style.css', $metadata);
            $storage->file('screenshot.png')->touch(); // TODO use no-image

            $storage->file('functions.php')->template($stub_path . '/functions.php', $metadata);

            $storage->file('blade/layout.blade.php')->template($stub_path . '/blade/layout.blade.php', $metadata);
            $storage->file('blade/index.blade.php')->template($stub_path . '/blade/index.blade.php', $metadata);

            $storage->file('lang/en/messages.php')->template($stub_path . '/lang/en/messages.php', $metadata);
        });
    }

    protected function generateSkeletonBootstrap($theme_name, array $metadata, $stub_path)
    {
        $this->storage->directory($theme_name, function ($storage) use ($metadata, $stub_path) {
            $storage->file('index.php')->template($stub_path . '/index.php', $metadata);
            $storage->file('style.css')->template($stub_path . '/style.css', $metadata);
            $storage->file('screenshot.png')->touch(); // TODO use no-image

            $storage->file('functions.php')->template($stub_path . '/functions.php', $metadata);

            $storage->file('blade/layout.blade.php')->template($stub_path . '/blade/layout.blade.php', $metadata);
            $storage->file('blade/index.blade.php')->template($stub_path . '/blade/index.blade.php', $metadata);

            $storage->file('lang/en/messages.php')->template($stub_path . '/lang/en/messages.php', $metadata);
        });
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Theme name.'],
            ['skeleton', InputArgument::OPTIONAL, 'Theme skeleton. [' . implode(', ', $this->skeletons) . ']'],
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
