<?php

namespace App\Http\Controllers\WordPress;

/**
 *
 */
class SiteAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('wordpress.site_admin_bootstrap');
    }

    public function siteDashboard()
    {
        $this->runAdminScriptWithMenu('network/index.php', [
            'wpdb',
            'current_site', 'current_blog', 'current_user',
        ]);
    }

    public function sitePage()
    {
        $script_path = app('request')->path();

        if (starts_with($script_path, 'wp-admin/')) {
            $script_path = substr($script_path, strlen('wp-admin/'));
        }

        $this->runAdminScriptWithMenu($script_path, [
            'wpdb',
            'current_site', 'current_blog',
        ]);
    }
}
