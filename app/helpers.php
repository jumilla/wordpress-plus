<?php

if (!function_exists('wordpress_path')) {
    function wordpress_path($path)
    {
        return base_path('wordpress/'.$path);
    }
}

if (!function_exists('wordpress')) {
    function wordpress($global_variable)
    {
        if (!array_key_exists($global_variable, app('wordpress.globals'))) {
            return;
        }

        return $GLOBALS[$global_variable];
    }
}

if (!function_exists('wordpress_table')) {
    function wordpress_table($table)
    {
        return env('WP_TABLE_PREFIX', 'wp_').$table;
    }
}

if (!function_exists('wordpress_installed')) {
    function wordpress_installed()
    {
        try {
            return app('db')->table(wordpress_table('options'))->exists();
        } catch (Exception $ex) {
            return false;
        }
    }
}

if (!function_exists('wordpress_multisite_installed')) {
    function wordpress_multisite_installed()
    {
        try {
            return app('db')->table(wordpress_table('site'))->exists();
        } catch (Exception $ex) {
            return false;
        }
    }
}

if (!function_exists('logger')) {
    function logger()
    {
        return app('Psr\Log\LoggerInterface');
    }
}

if (!function_exists('debug_log')) {
    /**
     * @signature debug_log(mixed $var)
     * @signature debug_log(string label, mixed $var, array $context = [])
     */
    function debug_log()
    {
        if (!env('APP_DEBUG', false)) {
            return;
        }

        if (func_num_args() < 1) {
            throw new InvalidArgumentException('Missing argument');
        } elseif (func_num_args() == 1) {
            $label = null;
            $var = func_get_arg(0);
            $context = [];
        } elseif (func_num_args() == 2) {
            $label = func_get_arg(0);
            $var = func_get_arg(1);
            $context = [];
        } elseif (func_num_args() >= 3) {
            $label = func_get_arg(0);
            $var = func_get_arg(1);
            $context = func_get_arg(2);
        }

        logger()->debug(($label ? "$label: " : '').print_r($var, true), $context);
    }
}

if (!function_exists('var_log')) {
    /**
     * @signature var_log(array $vars)
     * @signature var_log(string $name, mixed $var)
     */
    function var_log()
    {
        if (func_num_args() < 1) {
            throw new InvalidArgumentException('Missing argument');
        } elseif (func_num_args() == 1) {
            $vars = func_get_arg(0);
        } elseif (func_num_args() == 2) {
            $vars = [func_get_arg(0) => func_get_arg(1)];
        }

        foreach ($vars as $name => $var) {
            logger()->debug("[VAR] $name: ".print_r($var, true), []);
        }
    }
}
