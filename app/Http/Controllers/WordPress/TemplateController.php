<?php

namespace App\Http\Controllers\WordPress;

use Symfony\Component\Finder\Finder;

/**
 *
 */
class TemplateController extends Controller
{
    const TEMPLATE_TYPES = [
        // 'type'        => ['file-title-pattern'],
        'index'          => ['index'],
        'archive'        => ['archive-*', 'archive'],    // "archive-{$post->type}.php"
        'author'         => ['author-*', 'author'],        // "author-{$author->user_nicename}.php", "author-{$author->ID}.php"
        'category'       => ['category-*', 'category'],    // "category-{$category->slug}.php", "category-{$category->term_id}.php"
        'tag'            => ['tag-*', 'tag'],            // "tag-{$tag->slug}.php", "tag-{$tag->term_id}.php"
        'taxonomy'       => ['taxonomy-*', 'taxonomy'],    // "taxonomy-{$taxonomy}-{$term->slug}.php", "taxonomy-{$taxonomy}.php"
        'date'           => ['date'],
        'home'           => ['home', 'index'],
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
        app('view')->addNamespace('theme', get_template_directory());

        // Bladeファイルをコンパイルする
        $this->prepareTemplates();

        add_filter('template_include', [$this, 'evaluateTemplate']);

        /*
         * Tells WordPress to load the WordPress theme and output it.
         *
         * @var bool
         */
        define('WP_USE_THEMES', true);

debug_log('wordpress+', 'wp()');
        wp();

        /* Loads the WordPress Template */
debug_log('wordpress+', 'render');
        require wordpress_path('wp-includes/template-loader.php');
    }

    public function prepareTemplates()
    {
        /*
        $theme_root = get_template_directory();

        $files = Finder::create()->in($theme_root)->name('*.blade.php')->files();
        foreach ($files as $file) {
            $blade_path = $file->getPathname();
            $php_path = preg_replace('/\.blade\.php$/', '.php', $blade_path);
            $this->prepareTemplate($blade_path, $php_path);
        }
*/
        if (is_404()) {
            $this->prepareTemplate('404');
        } elseif (is_search()) {
            $this->prepareTemplate('search');
        } elseif (is_home()) {
            $this->prepareTemplate('home');
        } elseif (is_front_page()) {
            $this->prepareTemplate('front_page');
        } elseif (is_post_type_archive()) {
            $this->prepareTemplate('archive');
        } elseif (is_tax()) {
            $this->prepareTemplate('taxonomy');
        } elseif (is_attachment()) {
            $this->prepareTemplate('attachment');
        } elseif (is_single()) {
            $this->prepareTemplate('single');
        } elseif (is_page()) {
            $this->prepareTemplate('page');
        } elseif (is_category()) {
            $this->prepareTemplate('category');
        } elseif (is_tag()) {
            $this->prepareTemplate('tag');
        } elseif (is_author()) {
            $this->prepareTemplate('author');
        } elseif (is_date()) {
            $this->prepareTemplate('date');
        } elseif (is_archive()) {
            $this->prepareTemplate('archive');
        } elseif (is_comments_popup()) {
            $this->prepareTemplate('comments_popup');
        } elseif (is_paged()) {
            $this->prepareTemplate('paged');
        } else {
            $this->prepareTemplate('index');
        }
    }

    public function prepareTemplate($type, array $data = [])
    {
        debug_log('wordpress+', 'prepareTemplate:'.$type);

        $theme_root = get_template_directory();
        $patterns = static::TEMPLATE_TYPES[$type];

        foreach ($patterns as $pattern) {
            foreach (glob($theme_root.'/'.$pattern.'.blade.php') as $blade_path) {
                $php_path = preg_replace('/\.blade\.php$/', '.php', $blade_path);

//				// TODO ファイル更新日時によるチェック
//
//				// コンパイルしたPHPスクリプトを出力する
                try {
                    $view = app('view')->file($blade_path, $data);
                    file_put_contents($php_path.'.html', $view->render());
                } catch (\Exception $ex) {
                    continue;
                }

                touch($php_path);
            }
        }
    }

    public function evaluateTemplate($php_path)
    {
        debug_log('filter:template_include', $php_path);

        $dirname = dirname($php_path);
        $filename = basename($php_path, '.php');

        // blade extension
        $blade_path = preg_replace('/\.php$/', '.blade.php', $php_path);
        if (file_exists($blade_path)) {
            $results = $this->renderBladeTemplate($blade_path, []);
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
        // load from style.css ?
        return [
        ];
    }

    protected function renderBladeTemplate($path, array $data = [])
    {
        $results = app('view')->make('theme::'.basename($path, '.blade.php'), $data)->render();

        echo $results;

        return $results;
    }

    protected function renderPhpTemplate($path)
    {
        // evaluate
        include $path;
    }
}
