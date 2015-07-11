<?php

namespace App\Services;

class ContentClassLoader
{
    private static $instance;

    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public static function register()
    {
        spl_autoload_register([static::instance(), 'load'], true, false);
    }

    public static function unregister()
    {
        if (static::$instance) {
            spl_autoload_unregister([static::$instance, 'load']);

            static::$instance = null;
        }
    }

    public static function addNamespace($path, $namespace)
    {
        if (!empty($namespace)) {
            $namespace = trim($namespace, '\\').'\\';
        } else {
            $namespace = '';
        }

        static::instance()->root_pathes[$namespace][] = $path;
    }

    protected $root_pathes = [];

    public function load($class_name)
    {
        $class_namespace = preg_replace('/[^\\\\]+$/', '', $class_name);

        // PSR-4 class loader
        foreach ($this->root_pathes as $namespace => $root_pathes) {
            foreach ($root_pathes as $root_path) {
                if ($class_namespace === $namespace) {
                    $this->loadClass($root_path, substr($class_name, strlen($namespace)));
                }
            }
        }
    }

    public function loadClass($base_path, $relative_class_name)
    {
        $path = $base_path.'/'.str_replace('\\', '/', $relative_class_name).'.php';
        debug_log('try include', $path);

        if (file_exists($path)) {
            require_once $path;
        }
    }
}
