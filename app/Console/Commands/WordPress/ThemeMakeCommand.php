<?php

namespace App\Console\Commands\WordPress;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ThemeMakeCommand extends AbstractMakeCommand
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
        1 => 'minimum',
        2 => 'simple',
        3 => 'bootstrap',
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

        if ($this->storage->exists($theme_name)) {
            throw new \InvalidArgumentException("Theme directory '{$theme_name}' is already exists.");
        }

        $skeleton = $this->chooseSkeleton($this->argument('skeleton'));

        $metadata = $this->gatherMetadata($theme_name);

        $langs = $this->gatherLanguages();

        $method = 'generateSkeleton'.ucfirst($skeleton);
        $this->{$method}($theme_name, $metadata, $langs, 'theme-stubs/'.$skeleton);

        $this->info('success');
    }

    /**
     * gather metadata.
     *
     * @param string $theme_name
     * @return array
     */
    protected function gatherMetadata($theme_name)
    {
        $metadata = [];

        $metadata['lang'] = env('APP_LOCALE', 'en');
        $metadata['theme_name'] = trim($this->ask('Theme title', $theme_name));
        $metadata['theme_uri'] = trim($this->ask('Theme URI', ' '));
        $metadata['version'] = trim($this->ask('Version', '1.0'));
        $metadata['description'] = trim($this->ask('Description', ' '));
        $metadata['author'] = trim($this->ask('Author name', ' '));
        $metadata['author_uri'] = trim($this->ask('Author URI', ' '));
        $metadata['license'] = trim($this->ask('License', ' '));
        $metadata['license_uri'] = trim($this->ask('License URI', ' '));
        $metadata['tags'] = implode(', ', array_map(function ($value) { return trim($value); }, explode(',', $this->ask('Tags', ' '))));
        $metadata['php_autoload_dir'] = trim($this->ask('PHP autoload directory', 'classes'));
        $metadata['php_namespace'] = trim($this->ask('PHP namespace', ' '));

        return $metadata;
    }

    /**
     * gather languages.
     *
     * @return array
     */
    protected function gatherLanguages()
    {
        $langs[] = 'en';

        if (($locale = env('APP_LOCALE', 'en')) !== 'en') {
            $langs[] = $locale;
        }

        return $langs;
    }

    /**
     * generate skeleton type 'minimum'.
     *
     * @param string $theme_name
     * @param array  $metadata
     * @param array  $langs
     * @param string $stub_path
     */
    protected function generateSkeletonMinimum($theme_name, array $metadata, array $langs, $stub_path)
    {
        $this->storage->directory($theme_name, function ($storage) use ($metadata, $stub_path) {
            $storage->file('index.php')->template($stub_path.'/index.php', $metadata);
            $storage->file('style.css')->template($stub_path.'/style.css', $metadata);
            $storage->file('screenshot.png')->touch(); // TODO use no-image
            $storage->file('functions.php')->template($stub_path.'/functions.php', $metadata);
        });
    }

    /**
     * generate skeleton type 'simple'.
     *
     * @param string $theme_name
     * @param array  $metadata
     * @param array  $langs
     * @param string $stub_path
     */
    protected function generateSkeletonSimple($theme_name, array $metadata, array $langs, $stub_path)
    {
        $this->storage->directory($theme_name, function ($storage) use ($metadata, $langs, $stub_path) {
            $storage->file('index.php')->template($stub_path.'/index.php', $metadata);
            $storage->file('style.css')->template($stub_path.'/style.css', $metadata);
            $storage->file('screenshot.png')->touch(); // TODO use no-image

            $storage->file('functions.php')->template($stub_path.'/functions.php', $metadata);

            $storage->file('blade/layout.blade.php')->template($stub_path.'/blade/layout.blade.php', $metadata);
            $storage->file('blade/index.blade.php')->template($stub_path.'/blade/index.blade.php', $metadata);

            $storage->file('classes/.gitkeep')->template($stub_path.'/classes/.gitkeep', $metadata);

            foreach ($langs as $lang) {
                $storage->file('languages/'.$lang.'/messages.php')->template($stub_path.'/languages/en/messages.php', $metadata);
            }
        });
    }

    /**
     * generate skeleton type 'bootstrap'.
     *
     * @param string $theme_name
     * @param array  $metadata
     * @param string $stub_path
     */
    protected function generateSkeletonBootstrap($theme_name, array $metadata, array $langs, $stub_path)
    {
        $this->storage->directory($theme_name, function ($storage) use ($metadata, $langs, $stub_path) {
            $storage->file('index.php')->template($stub_path.'/index.php', $metadata);
            $storage->file('style.css')->template($stub_path.'/style.css', $metadata);
            $storage->file('screenshot.png')->touch(); // TODO use no-image

            $storage->file('functions.php')->template($stub_path.'/functions.php', $metadata);

            $storage->file('blade/layout.blade.php')->template($stub_path.'/blade/layout.blade.php', $metadata);
            $storage->file('blade/index.blade.php')->template($stub_path.'/blade/index.blade.php', $metadata);

            $storage->file('classes/.gitkeep')->template($stub_path.'/classes/.gitkeep', $metadata);

            foreach ($langs as $lang) {
                $storage->file('languages/'.$lang.'/messages.php')->template($stub_path.'/languages/en/messages.php', $metadata);
            }
        });
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Theme name.'],
            ['skeleton', InputArgument::OPTIONAL, 'Theme skeleton. ['.implode(', ', $this->skeletons).']'],
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
