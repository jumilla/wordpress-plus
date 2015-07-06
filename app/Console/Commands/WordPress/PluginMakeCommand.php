<?php

namespace App\Console\Commands\WordPress;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PluginMakeCommand extends AbstractMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:plugin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make wordpress plugin.';

    /**
     * @var array
     */
    protected $skeletons = [
        'minimum',
        'simple',
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

        $this->storage = new Storage('wordpress/wp-content/plugins');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $plugin_name = $this->argument('name');

        if ($this->storage->exists($plugin_name)) {
            throw new \InvalidArgumentException("Plugin directory '{$plugin_name}' is already exists.");
        }

        $skeleton = $this->chooseSkeleton($this->argument('skeleton'));

        $metadata = $this->gatherMetadata($plugin_name);

        $method = 'generateSkeleton' . ucfirst($skeleton);
        $this->{$method}($plugin_name, $metadata, 'plugin-stubs/' . $skeleton);

        $this->info('success');
    }

    protected function gatherMetadata($plugin_name)
    {
        $metadata = [];

        $metadata['lang'] = env('APP_LOCALE', 'en');
        $metadata['plugin_name'] = trim($this->ask('Theme title', $plugin_name));
        $metadata['plugin_uri'] = trim($this->ask('Theme URI', ' '));
        $metadata['version'] = trim($this->ask('Version', '1.0'));
        $metadata['description'] = trim($this->ask('Description', ' '));
        $metadata['author'] = trim($this->ask('Author name', ' '));
        $metadata['author_uri'] = trim($this->ask('Author URI', ' '));
        $metadata['license'] = trim($this->ask('License', ' '));
        $metadata['license_uri'] = trim($this->ask('License URI', ' '));
        $metadata['php_autoload_dir'] = trim($this->ask('PHP autoload directory', 'classes'));
        $metadata['php_namespace'] = trim($this->ask('PHP namespace', ' '));

        return $metadata;
    }
    
    /**
     * generate skeleton type 'minimum'
     *
     * @param string $plugin_name
     * @param array  $metadata
     * @param string $stub_path
     */
    protected function generateSkeletonMinimum($plugin_name, array $metadata, $stub_path)
    {
        $this->storage->directory($plugin_name, function ($storage) use ($plugin_name, $metadata, $stub_path) {
            $storage->file($plugin_name . '.php')->template($stub_path . '/main.php', $metadata);
        });
    }

    /**
     * generate skeleton type 'simple'
     *
     * @param string $plugin_name
     * @param array  $metadata
     * @param string $stub_path
     */
    protected function generateSkeletonSimple($plugin_name, array $metadata, $stub_path)
    {
        $this->storage->directory($plugin_name, function ($storage) use ($plugin_name, $metadata, $stub_path) {
            $storage->file($plugin_name . '.php')->template($stub_path . '/main.php', $metadata);
        });
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Plugin name.'],
            ['skeleton', InputArgument::OPTIONAL, 'Plugin skeleton. [' . implode(', ', $this->skeletons) . ']'],
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
