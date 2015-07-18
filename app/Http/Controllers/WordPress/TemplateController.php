<?php

namespace App\Http\Controllers\WordPress;

use Symfony\Component\Finder\Finder;
use Illuminate\Support\Fluent;
use App\Services\BladeExpander;

/**
 *
 */
class TemplateController extends Controller
{
    const TEMPLATE_TYPES = [
        // 'type'        => ['file-title-pattern'],
        'index'          => ['index'],
        'archive'        => ['archive-*', 'archive'],      // "archive-{$post->type}.php"
        'author'         => ['author-*', 'author'],        // "author-{$author->user_nicename}.php", "author-{$author->ID}.php"
        'category'       => ['category-*', 'category'],    // "category-{$category->slug}.php", "category-{$category->term_id}.php"
        'tag'            => ['tag-*', 'tag'],              // "tag-{$tag->slug}.php", "tag-{$tag->term_id}.php"
        'taxonomy'       => ['taxonomy-*', 'taxonomy'],    // "taxonomy-{$taxonomy}-{$term->slug}.php", "taxonomy-{$taxonomy}.php"
        'date'           => ['date'],
        'home'           => ['home'],
        'front_page'     => ['front-page'],
        'page'           => ['page-*', 'page'],            // "{$slug}", "page-{$pagename}.php", "page-{$id}.php"
        'paged'          => ['paged'],
        'search'         => ['search'],
        'single'         => ['single-*', 'single'],        // "single-{$object->post_type}.php"
        'attachment'     => ['attachment'],                // $type[0], $type[1] ???
        'comments_popup' => ['comments-popup'],
        '404'            => ['404'],
    ];

    public function __construct()
    {
        $this->middleware('wordpress.template_bootstrap');
    }

    public function provide()
    {
        $this->setupLaravelEnvironment();

        /*
         * Tells WordPress to load the WordPress theme and output it.
         *
         * @var bool
         */
        define('WP_USE_THEMES', true);

        // Process
        wp();

        // remove admin redirect action
        remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);

        // Generate blank .php files
        $this->prepareTemplates(false);

        // Install high priority filter
        add_filter('template_include', [$this, 'evaluateTemplate'], 1);

        /* Loads the WordPress Template */
        return $this->runScript('wp-includes/template-loader.php');
    }

    protected function setupLaravelEnvironment()
    {
        app('view')->addNamespace('theme', $this->themeBladeDirectory());
        app('translator')->addNamespace('theme', $this->themeLangDirectory());

        foreach (get_option('active_plugins') as $plugin_path) {
            list($plugin, $plugin_directory) = $this->parsePluginPath($plugin_path);

            if ($plugin_directory) {
                app('translator')->addNamespace($plugin, $plugin_directory.'/'.config('wordpress.plugins.lang.directory'));
            }
        }
    }

    protected function themeBladeDirectory()
    {
        return get_template_directory().'/'.config('wordpress.themes.blade.directory');
    }

    protected function themeLangDirectory()
    {
        return get_template_directory().'/'.config('wordpress.themes.lang.directory');
    }

    protected function parsePluginPath($plugin_path)
    {
        if (preg_match('/^(.+)\/.+.php$/', $plugin_path, $match)) {
            return [$match[1], WP_PLUGIN_DIR.'/'.$match[1]];
        } elseif (preg_match('/^([!\/])+.php$/', $plugin_path, $match)) {
            return [$match[1], null];
        } else {
            return;
        }
    }

    /**
     * @param bool $compile
     */
    protected function prepareTemplates($compile)
    {
        if (is_404()) {
            $this->prepareTemplate('404');
        }
        if (is_search()) {
            $this->prepareTemplate('search');
        }
        if (is_front_page()) {
            $this->prepareTemplate('front_page');
        }
        if (is_home()) {
            $this->prepareTemplate('home');
        }
        if (is_post_type_archive()) {
            $this->prepareTemplate('archive');
        }
        if (is_tax()) {
            $this->prepareTemplate('taxonomy');
        }
        if (is_attachment()) {
            $this->prepareTemplate('attachment');
        }
        if (is_single()) {
            $this->prepareTemplate('single');
        }
        if (is_page()) {
            $this->prepareTemplate('page');
        }
        if (is_category()) {
            $this->prepareTemplate('category');
        }
        if (is_tag()) {
            $this->prepareTemplate('tag');
        }
        if (is_author()) {
            $this->prepareTemplate('author');
        }
        if (is_date()) {
            $this->prepareTemplate('date');
        }
        if (is_archive()) {
            $this->prepareTemplate('archive');
        }
        if (is_comments_popup()) {
            $this->prepareTemplate('comments_popup');
        }
        if (is_paged()) {
            $this->prepareTemplate('paged');
        }
        $this->prepareTemplate('index');
    }

    protected function prepareTemplate($type)
    {
        debug_log('wordpress+', 'prepareTemplate:'.$type);

        $blade_root = $this->themeBladeDirectory();
        $patterns = static::TEMPLATE_TYPES[$type];

        foreach ($patterns as $pattern) {
            foreach (glob($blade_root.'/'.$pattern.'.blade.php') as $blade_path) {
                // make wordpress specified path
                $php_path = get_template_directory().'/'.basename($blade_path, '.blade.php').'.php';

                touch($php_path);
            }
        }
    }

    /**
     * @wp_action template_include
     */
    public function evaluateTemplate($php_path)
    {
        debug_log('filter:template_include', basename($php_path));

        $dirname = dirname($php_path);
        $filename = basename($php_path, '.php');

        // blade extension
        $blade_path = $dirname.'/'.config('wordpress.themes.blade.directory').'/'.$filename.'.blade.php';
        if (file_exists($blade_path)) {
            if (config('wordpress.themes.blade.precompile')) {
                $header_comment = config('wordpress.themes.blade.header_comment');
                $php_script_image = app('blade.expander')->expand($blade_path);

                // Output php
                file_put_contents($php_path, "<?php /* {$header_comment} */ ?>\n");
                file_put_contents($php_path, $php_script_image, FILE_APPEND);
            } else {
                // Output html
                file_put_contents($php_path, $this->renderBladeTemplate($blade_path));
            }
        }

        return $php_path;
    }

    protected function renderBladeTemplate($path)
    {
        return app('view')->make('theme::'.basename($path, '.blade.php'), [])->render();
    }
}
