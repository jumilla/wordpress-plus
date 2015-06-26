<?php

namespace App\Services;

trait WordPressService
{
    protected function adjustServerVariables()
    {
        // set script name for 'wordpress/wp-includes/vars.php'
        // for NGINX
        if (isset($_SERVER['PATH_INFO'])) {
            $_SERVER['PHP_SELF'] = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['PHP_SELF'] = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);
        }
    }

    protected function getGlobalsKeys()
    {
        return array_keys($GLOBALS);
    }

    protected function detectNewGlobals(array $globals_before_keys)
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

    protected function runTemplateBootstrapScript()
    {
        if (env('WP_MULTISITE', false)) {
            // for 'wp-includes/ms-functions.php'
            global $current_site;
            global $current_blog;

            // for 'wp-includes/ms-settings.php'
            global $blog_id;
            global $wpdb;
            global $_wp_switched_stack;
        }
        else {

        }

        require_once wordpress_path('wp-load.php');
    }

    protected function runAdminBootstrapScript()
    {
        // for 'wp-admin/includes/file.php'
        global $wp_file_descriptions;

        if (env('WP_MULTISITE', false)) {
            // for 'wp-includes/ms-functions.php'
            global $current_site;
            global $current_blog;

            // for 'wp-includes/ms-settings.php'
            global $blog_id;
            global $wpdb;
            global $_wp_switched_stack;
        }
        else {
        }

        require_once wordpress_path('wp-load.php');
        require_once wordpress_path('wp-admin/includes/admin.php');
    }

    protected function runAdminScriptWithMenu($filename, array $globals = [])
    {
        $globals = array_merge($globals, ['menu', 'submenu', '_wp_menu_nopriv', '_wp_submenu_nopriv']);

        // for sort_menu() in wp-admin/includes/menu.php
        $globals = array_merge($globals, ['menu_order', 'default_menu_order']);

        // for wp-admin/includes/plugin.php
        $globals = array_merge($globals, ['_wp_last_object_menu', '_wp_last_utility_menu']);

        $this->runAdminScript($filename, $globals);
    }

    protected function runAdminScript($filename, array $globals = [])
    {
        // from wp-settings.php
        $globals = array_merge($globals, ['wp_version', 'wp_db_version', 'tinymce_version', 'required_php_version', 'required_mysql_version']);

        $this->runScript('wp-admin/'.$filename, $globals);
    }

    protected function runScript($path, array $globals = [])
    {
        // add script specified global variables
        $globals = array_merge($globals, WordPress::globals($path) ?: []);

        foreach ($globals as $global) {
            global ${$global};
        }

        require wordpress_path($path);
    }
}
