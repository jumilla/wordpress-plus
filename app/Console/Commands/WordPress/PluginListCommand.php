<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PluginListCommand extends Command
{
    use EnvironmentTrait;
    use \App\Services\WordPressService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wordpress:plugin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List wordpress plugins.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // entrance
        $this->logo();

        // prepare
        $this->runAdminBootstrapScript();
        add_filter('extra_plugin_headers', function (array $headers) {
            return array_merge($headers, [
                'PHP Autoload',
                'PHP Namespace',
            ]);
        });

        $plugin = $this->argument('name');
        if (!$plugin) {
            // title
            $this->info('-- WordPress Plugin List --');

            // process
            $this->outputPluginList();
        } else {
            // title
            $this->info('-- WordPress Plugin Info --');

            // process
            $this->outputPluginInfo($plugin);
        }

        $this->line('');
    }

    protected function outputPluginList()
    {
        $plugins = get_plugins();
        $active_plugins = get_option('active_plugins');
        $active_sitewide_plugins = is_multisite() ? get_site_option('active_sitewide_plugins') : [];

        foreach ($plugins as $plugin_path => $plugin_data) {
            $plugin = preg_replace('/(\/.*$)|(.php$)/', '', $plugin_path);
            $mark = in_array($plugin_path, $active_plugins, true) ? '*' : (isset($active_sitewide_plugins[$plugin_path]) ? '+' : ' ');
            $this->line("{$mark} {$plugin_data['Name']} [{$plugin_data['Version']}] '{$plugin}'");
        }
    }

    protected function outputPluginInfo($plugin)
    {
        $plugin_path = WP_PLUGIN_DIR.'/'.$plugin.'/'.$plugin.'.php';
        $plugin_type_dir = true;

        if (!file_exists($plugin_path)) {
            $plugin_path = WP_PLUGIN_DIR.'/'.$plugin.'.php';
            $plugin_type_dir = false;

            if (!file_exists($plugin_path)) {
                throw new \InvalidArgumentException("Plugin '{$plugin}' is not found.");
            }
        }

        $plugin_data = get_plugin_data($plugin_path, false, true);

        $this->line("<info>[Plugin file]</info> '{$plugin_path}'");
        $properties_wordpress = [
            'name' => 'Name',
            'title' => 'Title',
            'version' => 'Version',
            'plugin_uri' => 'PluginURI',
            'description' => 'Description',
            'author' => 'Author',
            'author_name' => 'AuthorName',
            'author_uri' => 'AuthorURI',
//            'tags' => '',
            'text_domain' => 'TextDomain',
            'domain_path' => 'DomainPath',
            'network' => 'Network',
        ];
        foreach ($properties_wordpress as $property => $key) {
            $value = array_get($plugin_data, $key);
            if (is_array($value)) {
                $value = '['.implode(', ', $value).']';
            }
            $this->line("<info>[{$property}]</info> {$value}");
        }

        $properties_extra = [
            'php_namespace' => 'PHP Namespace',
        ];
        foreach ($properties_extra as $property => $key) {
            $value = array_get($plugin_data, $key);
            if (is_array($value)) {
                $value = '['.implode(', ', $value).']';
            }
            $this->line("<info>[{$property}]</info> {$value}");
        }
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'Plugin name', null],
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
