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

    protected function bladeDirectory()
    {
        return get_template_directory() . '/' . config('wordpress.themes.blade.directory');
    }

    protected function langDirectory()
    {
        return get_template_directory() . '/' . config('wordpress.themes.lang.directory');
    }

    public function provide()
    {
        app('view')->addNamespace('theme', $this->bladeDirectory());
        app('translator')->addNamespace('theme', $this->langDirectory());

        /*
         * Tells WordPress to load the WordPress theme and output it.
         *
         * @var bool
         */
        define('WP_USE_THEMES', true);

        // Process
        wp();

        if (config('wordpress.themes.blade.precompile')) {
            // Bladeファイルをコンパイルする
            $this->prepareTemplates(true);

            add_filter('template_include', [$this, 'renderPhpTemplate']);
        }
        else {
            $this->prepareTemplates(false);

            add_filter('template_include', [$this, 'evaluateTemplate']);
        }

        /* Loads the WordPress Template */
        require wordpress_path('wp-includes/template-loader.php');
    }

    public /* action_hook */ function prepareTemplates($compile)
    {
        if (is_404()) {
            $this->prepareTemplate('404', $compile);
        }
        if (is_search()) {
            $this->prepareTemplate('search', $compile);
        }
        if (is_front_page()) {
            $this->prepareTemplate('front_page', $compile);
        }
        if (is_home()) {
            $this->prepareTemplate('home', $compile);
        }
        if (is_post_type_archive()) {
            $this->prepareTemplate('archive', $compile);
        }
        if (is_tax()) {
            $this->prepareTemplate('taxonomy', $compile);
        }
        if (is_attachment()) {
            $this->prepareTemplate('attachment', $compile);
        }
        if (is_single()) {
            $this->prepareTemplate('single', $compile);
        }
        if (is_page()) {
            $this->prepareTemplate('page', $compile);
        }
        if (is_category()) {
            $this->prepareTemplate('category', $compile);
        }
        if (is_tag()) {
            $this->prepareTemplate('tag', $compile);
        }
        if (is_author()) {
            $this->prepareTemplate('author', $compile);
        }
        if (is_date()) {
            $this->prepareTemplate('date', $compile);
        }
        if (is_archive()) {
            $this->prepareTemplate('archive', $compile);
        }
        if (is_comments_popup()) {
            $this->prepareTemplate('comments_popup', $compile);
        }
        if (is_paged()) {
            $this->prepareTemplate('paged', $compile);
        }
        $this->prepareTemplate('index', $compile);
    }

    protected function prepareTemplate($type, $compile)
    {
        debug_log('wordpress+', 'prepareTemplate:'.$type);

        $blade_root = $this->bladeDirectory();
        $patterns = static::TEMPLATE_TYPES[$type];

        foreach ($patterns as $pattern) {
            foreach (glob($blade_root.'/'.$pattern.'.blade.php') as $blade_path) {
                // make wordpress specified path
                $php_path = get_template_directory().'/'.basename($blade_path, '.blade.php').'.php';

                // Need precompile
                if ($compile) {
                    $header_comment = config('wordpress.themes.blade.header_comment');
                    $php_script_image = app('blade.expander')->expand($blade_path);

                    file_put_contents($php_path, "<?php /* {$header_comment} */ ?>\n");
                    file_put_contents($php_path, $php_script_image, FILE_APPEND);
                }
                else {
                    touch($php_path);
                }
            }
        }
    }

    public /* action_hook */ function evaluateTemplate($php_path)
    {
        debug_log('filter:template_include', basename($php_path));

        $dirname = dirname($php_path);
        $filename = basename($php_path, '.php');

        // blade extension
        $blade_path = preg_replace('/\.php$/', '.blade.php', $php_path);
        if (file_exists($blade_path)) {
            $this->renderBladeTemplate($blade_path);
        } elseif (file_exists($php_path)) {
            $this->renderPhpTemplate($php_path);
        } else {
            return $php_path;
        }

        // consumed
        return;
    }

    protected function loadMetaData()
    {
        // TODO load from style.css ?
        return new Fluent([
            'lumen_version' => app()->version(),
        ]);
    }

    protected function renderBladeTemplate($path)
    {
        $metadata = $this->loadMetaData();

        $results = app('view')->make('theme::'.basename($path, '.blade.php'), compact('metadata'))->render();

        echo $results;
    }

    public /* action_hook */ function renderPhpTemplate($path)
    {
        $metadata = $this->loadMetaData();

        // evaluate
        include $path;
    }
}
