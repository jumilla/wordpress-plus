<?php

namespace App\Http\Middleware\WordPress;

use Closure;

class AdminBootstrapMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->adjustServerVariables();

        // save keys for $GLOBALS
        $globals_before_keys = array_keys($GLOBALS);

        // load wordpress objects
        $this->bootstrap();

        // detect newers
        $wordpress_globals = $this->detectNewGlobals($globals_before_keys);

        // register to service container
        app()->instance('wordpress.globals', $wordpress_globals);
//        info(print_r(app('wordpress.globals'), true));

//        // replace wp_reset_vars()
//        require_once __DIR__ . '/wp_reset_vars.php';

        return $next($request);
    }

    private function adjustServerVariables()
    {
        // set script name for 'wordpress/wp-includes/vars.php'
        // for NGINX
        if (isset($_SERVER['PATH_INFO'])) {
            $_SERVER['PHP_SELF'] = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['PHP_SELF'] = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);
        }
    }

    private function bootstrap()
    {
        /*
         * In WordPress Administration Screens
         *
         * @since 2.3.2
         */
        if (!defined('WP_ADMIN')) {
            define('WP_ADMIN', true);
        }

        if (!defined('WP_NETWORK_ADMIN')) {
            define('WP_NETWORK_ADMIN', false);
        }

        if (!defined('WP_USER_ADMIN')) {
            define('WP_USER_ADMIN', false);
        }

//        if ( ! WP_NETWORK_ADMIN && ! WP_USER_ADMIN ) {
//            define('WP_BLOG_ADMIN', true);
//        }

        $this->runBootstrapScript();

        if (env('WP_LINK_MANAGER', false)) {
            add_filter('pre_option_link_manager_enabled', '__return_true');
        }
    }

    private function runBootstrapScript()
    {
        // for 'wp-admin/includes/file.php'
        global $wp_file_descriptions;

        // for ???
        global $wpdb;

        // for 'wp-includes/ms-functions.php'
        global $current_site;
        global $current_blog;

        require_once wordpress_path('wp-load.php');
        require_once wordpress_path('wp-admin/includes/admin.php');
    }

    private function detectNewGlobals(array $globals_before_keys)
    {
        // retrieve & sort keys for $GLOBALS
        $globals_keys = array_keys($GLOBALS);
        sort($globals_keys);

        $new_globals = [];

        // enumerate keys
        foreach ($globals_keys as $key) {
            if (!in_array($key, $globals_before_keys)) {
                //                info('New GLOBAL: ' . $key);
                $new_globals[] = $key;
            }
//            else
//                info('Exists: ' . $key);
        }

        return $new_globals;
    }
}
