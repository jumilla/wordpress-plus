<?php

namespace App\Services;

class WordPress
{
    /** @var array */
    private static $definition;

    /** @var array */
    private static $script_globals;

    public static function blogAdminScripts()
    {
        static::setup();

        return array_keys(static::$definition['blog_admin_scripts']);
    }

    public static function siteAdminScripts()
    {
        static::setup();

        return array_keys(static::$definition['site_admin_scripts']);
    }

    public static function scriptGlobals()
    {
        static::setup();

        return static::$script_globals;
    }

    public static function globals($script_path)
    {
        return array_get(static::scriptGlobals(), $script_path);
    }

    public static function activePlugins()
    {
        return get_option('active_plugins');
    }

    public static function pluginPath($plugin)
    {
        $path = wordpress_path('wp-content/plugins/').$plugin.'/'.$plugin.'.php';

        if (!file_exists($path)) {
            $path = wordpress_path('wp-content/plugins/').$plugin.'.php';

            if (!file_exists($path)) {
                return false;
            }
        }

        return $path;
    }

    public static function activeTheme()
    {
        return get_template();
    }

    public static function themePath($theme)
    {
        $path = wordpress_path('wp-content/themes/').$theme;

        if (!is_dir($path)) {
            return false;
        }

        return $path;
    }

    protected static function setup()
    {
        if (static::$definition === null) {
            static::$definition = require __DIR__.'/definition.php';
        }

        if (static::$script_globals === null) {
            static::$script_globals = static::buildScriptGlobals();
        }
    }

    protected static function buildScriptGlobals()
    {
        $script_globals = [];

        // Step.1
        // blog admin
        foreach (static::$definition['blog_admin_scripts'] as $path => $globals) {
            if (!is_array($globals)) {
                throw new \Exception('Miss configuration.');
            }

            $script_globals['wp-admin/'.$path] = $globals ?: [];
        }

        // Step.2
        // site admin
        foreach (static::$definition['site_admin_scripts'] as $path => $additional_globals) {
            if (!is_array($additional_globals)) {
                throw new \Exception('Miss configuration.');
            }

            $globals = array_get(static::$definition['blog_admin_scripts'], $path, []);

            $script_globals['wp-admin/network/'.$path] = array_merge($globals, $additional_globals);
        }

        return $script_globals;
    }
}
