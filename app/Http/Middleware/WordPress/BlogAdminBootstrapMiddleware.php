<?php

namespace App\Http\Middleware\WordPress;

use Closure;

class BlogAdminBootstrapMiddleware
{
    use \App\Services\WordPressService;

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
        $globals_before_keys = $this->getGlobalsKeys();

        // load wordpress objects
        $this->bootstrap();

        // detect newers
        $wordpress_globals = $this->detectNewGlobals($globals_before_keys);

        // register to service container
        app()->instance('wordpress.globals', $wordpress_globals);
//        info(print_r(app('wordpress.globals'), true));

        return $next($request);
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

        if (!WP_NETWORK_ADMIN && !WP_USER_ADMIN) {
            define('WP_BLOG_ADMIN', true);
        }

        if (isset($_GET['import']) && !defined('WP_LOAD_IMPORTERS')) {
            define('WP_LOAD_IMPORTERS', true);
        }

        $this->runAdminBootstrapScript();

        if (env('WP_LINK_MANAGER', false)) {
            add_filter('pre_option_link_manager_enabled', '__return_true');
        }
    }
}
