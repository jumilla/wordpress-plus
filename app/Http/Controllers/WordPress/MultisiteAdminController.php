<?php

namespace App\Http\Controllers\WordPress;

/**
 *
 */
class MultisiteAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('wordpress.multisite_admin_bootstrap');
    }

    public function multisiteDashboard()
    {
//        info('multisiteDashboard');
        $this->runAdminScriptWithMenu('wp-admin/network/index.php', [
            'wpdb',
            'current_site', 'current_blog', 'current_user',
        ]);
    }

    public function multisitePage()
    {
        $script_path = app('request')->path();

        $this->runAdminScriptWithMenu($script_path, [
            'wpdb',
            'current_site', 'current_blog',
        ]);
    }

    private function runAdminScript($filename, array $globals = [])
    {
        // from wp-settings.php
        $globals = array_merge($globals, ['wp_version', 'wp_db_version', 'tinymce_version', 'required_php_version', 'required_mysql_version']);

        $this->runScript($filename, $globals);
    }

    private function runAdminScriptWithMenu($filename, array $globals = [])
    {
        $globals = array_merge($globals, ['menu', 'submenu', '_wp_menu_nopriv', '_wp_submenu_nopriv']);

        // for sort_menu() in wp-admin/includes/menu.php
        $globals = array_merge($globals, ['menu_order', 'default_menu_order']);

        // for wp-admin/includes/plugin.php
        $globals = array_merge($globals, ['_wp_last_object_menu', '_wp_last_utility_menu']);

        $this->runAdminScript($filename, $globals);
    }
}
