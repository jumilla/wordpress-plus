<?php

namespace App\Console\Commands\WordPress;

use Closure;
use InvalidArgumentException;

class Storage
{
    protected $storage;

    protected $directory_path;

    protected $file_path;

    public function __construct($path)
    {
        $this->storage = app('filesystem')->createLocalDriver([
            'root' => base_path($path),
        ]);
    }

    public function exists($path)
    {
        return $this->storage->exists($path);
    }

    public function directory($path, Closure $closure = null)
    {
        $this->storage->makeDirectory($path);

        if ($closure) {
            $old_directory_path = $this->directory_path;

            $this->directory_path = $this->makePath($path);

            call_user_func($closure, $this);

            $this->directory_path = $old_directory_path;
        }
    }

    public function file($path)
    {
        $this->file_path = $this->makePath($path);

        return $this;
    }

    public function touch()
    {
        $this->storage->put($this->file_path, '');
    }

    public function string($content = null)
    {
        $this->storage->put($this->file_path, $content);
    }

    public function template($stub_path, array $arguments = [])
    {
        $stub_path = __DIR__.'/'.$stub_path;
        $content = file_get_contents($stub_path);

        if ($content === false) {
            throw new InvalidArgumentException("File '$stub_path' is not found.");
        }

        foreach ($arguments as $name => $value) {
            $content = preg_replace('/\{\s*\$'.$name.'\s*\}/', $value, $content);
        }

        $this->storage->put($this->file_path, $content);
    }

    protected function makePath($path)
    {
        return $this->directory_path ? $this->directory_path.'/'.$path : $path;
    }
}
