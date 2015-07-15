<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ThemeListCommand extends Command
{
    use EnvironmentTrait;
    use \App\Services\WordPressService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wordpress:theme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List wordpress themes.';

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
        $this->runTemplateBootstrapScript();
        add_filter('extra_theme_headers', function (array $headers) {
            return array_merge($headers, [
                'PHP Autoload',
                'PHP Namespace',
            ]);
        });

        $plugin = $this->argument('name');
        if (!$plugin) {
            // title
            $this->info('-- WordPress Theme List --');

            // process
            $this->outputThemeList();
        } else {
            // title
            $this->info('-- WordPress Theme Info --');

            // process
            $this->outputThemeInfo($plugin);
        }

        // process
        $this->line('');
    }

    protected function outputThemeList()
    {
        $themes = wp_get_themes();

        foreach ($themes as $directory => $theme) {
            $mark = (get_template() === $directory) ? '*' : ' ';
            $this->line("{$mark} {$theme->name} [{$theme->version}] '{$directory}'");
        }
    }

    protected function outputThemeInfo($theme)
    {
        $theme_path = wordpress_path('wp-content/themes/').$theme;

        // check
        if (!file_exists($theme_path)) {
            throw new \InvalidArgumentException("Theme '{$theme}' is not found.");
        }

        $theme_object = new \WP_Theme($theme, dirname($theme_path));

//        // MEMO: template_dir と同じ  
//        $this->line("<info>[path]</info> '{$theme_path}'");

        $properties_wordpress = [
            'name', 'title', 'version', 'parent_theme',
            'template_dir', 'stylesheet_dir', 'template', 'stylesheet',
            'screenshot', 'description', 'author', 'tags',
            'theme_root', 'theme_root_uri',
        ];
        foreach ($properties_wordpress as $property) {
            $value = $theme_object->{$property};
            if (is_array($value)) {
                $value = '['.implode(', ', $value).']';
            }
            $this->line("<info>[{$property}]</info> {$value}");
        }

        $properties_extra = [
            'php_namespace' => 'PHP Namespace',
        ];
        foreach ($properties_extra as $property => $field) {
            $value = $theme_object->get($field);
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
