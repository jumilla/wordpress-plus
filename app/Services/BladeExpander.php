<?php

namespace App\Services;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;

class BladeExpander extends BladeCompiler
{
	protected $sectionStack = [];

	protected $sections = [];

	public function expand($path, array $data = [])
	{
		return $this->compileString(file_get_contents($path));
//		return $this->compile($path);

		app('view')->addExtension('blade.php', 'blade-expander', function () {
			return new CompilerEngine(new static(app('files'), storage_path('framework/views')));
		});

		$env = app('view');

		return $env->make('theme::front-page', $data)->render();
	}

	public function processInclude($view, array $data = [])
	{
		// normalize

		// find file
		$path = app('view')->getFinder()->find($view);

		return $this->expand($path, $data);
	}

    /**
     * Compile the yield statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileYield($expression)
    {
    	debug_log('compileYield');
        return "<?php echo \$__env->yieldContent{$expression}; ?>";
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileShow($expression)
    {
    	debug_log('compileShow');
        return "<?php echo \$__env->yieldSection(); ?>";
    }

    /**
     * Compile the section statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileSection($expression)
    {
    	debug_log('compileSection');
        return "<?php \$__env->startSection{$expression}; ?>";
    }

    /**
     * Compile the append statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileAppend($expression)
    {
    	debug_log('compileAppend');
        return "<?php \$__env->appendSection(); ?>";
    }

    /**
     * Compile the end-section statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileEndsection($expression)
    {
    	debug_log('compileEndsection');
        return "<?php \$__env->stopSection(); ?>";
    }

    /**
     * Compile the stop statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileStop($expression)
    {
    	debug_log('compileStop');
        return "<?php \$__env->stopSection(); ?>";
    }

    /**
     * Compile the overwrite statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileOverwrite($expression)
    {
    	debug_log('compileOverwrite');
        return "<?php \$__env->stopSection(true); ?>";
    }

    /**
     * Compile the extends statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileExtends($expression)
    {
    	debug_log('compileExtends');
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileInclude($expression)
    {
    	debug_log('compileInclude');

    	return eval("return \$this->processInclude{$expression};");
    }

    /**
     * Compile the stack statements into the content.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileStack($expression)
    {
    	debug_log('compileStack');
    	return '';
        return "<?php echo \$__env->yieldContent{$expression}; ?>";
    }

    /**
     * Compile the push statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePush($expression)
    {
    	debug_log('compilePush');
    	return '';
        return "<?php \$__env->startSection{$expression}; ?>";
    }

    /**
     * Compile the endpush statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileEndpush($expression)
    {
    	debug_log('compileEndpush');
    	return '';
        return "<?php \$__env->appendSection(); ?>";
    }

}
