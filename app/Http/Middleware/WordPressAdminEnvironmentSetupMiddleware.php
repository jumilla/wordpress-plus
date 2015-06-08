<?php namespace App\Http\Middleware;

use Closure;

class WordPressAdminEnvironmentSetupMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // save keys for $GLOBALS
        $globals_before_keys = array_keys($GLOBALS);

        // load wordpress objects
        require_once(base_path('wordpress/wp-load.php'));

//        info('Global Check');

        $wordpress_globals = $this->detectNewGlobals($globals_before_keys);

        // register to service container
        app()->instance('wordpress.globals', $wordpress_globals);

//        info(print_r(app('wordpress.globals'), true));

        return $next($request);
    }

    private function detectNewGlobals(array $globals_before_keys)
    {
        // retrieve & sort keys for $GLOBALS
        $globals_keys = array_keys($GLOBALS);
        sort($globals_keys);

        $new_globals = [];

        // enumerate keys
        foreach ($globals_keys as $key) {
            if (! in_array($key, $globals_before_keys)) {
//                info('New GLOBAL: ' . $key);
                $new_globals[] = $key;
            }
//            else
//                info('Exists: ' . $key);
        }

        return $new_globals;
    }

}
