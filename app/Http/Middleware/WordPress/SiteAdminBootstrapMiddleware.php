<?php

namespace App\Http\Middleware\WordPress;

use Closure;

class SiteAdminBootstrapMiddleware extends BlogAdminBootstrapMiddleware
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
        define('WP_NETWORK_ADMIN', true);

        return parent::handle($request, $next);
    }
}
