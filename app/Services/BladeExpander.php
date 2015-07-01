<?php

namespace App\Services;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;

class BladeExpander extends BladeCompiler
{
	protected $sections = [];

	protected $extend;
	protected $sectionStack;
	protected $currentSectionName;
	protected $currentSection;

	public function expand($path, array $data = [])
	{
        $this->sections = [];

        $this->extend = null;
        $this->sectionStack = [];

		$this->expandFile($path);

        if (isset($this->extend)) {
        	$this->processInclude($this->extend, []);
        }

        return $this->currentSection;
	}

	protected function expandFile($path, array $data = [])
	{
		$this->expandString(file_get_contents($path));
	}

    /**
     * Compile the given Blade template contents.
     *
     * @param  string  $value
     * @return string
     */
    protected function expandString($value)
    {
        // Here we will loop through all of the tokens returned by the Zend lexer and
        // parse each one into the corresponding valid PHP. We will then have this
        // template as the correctly rendered PHP that can be rendered natively.
        foreach (token_get_all($value) as $token) {
        	if (is_array($token)) {
		        list($id, $content) = $token;
		        if ($id == T_INLINE_HTML) {
			        preg_match_all('/\B@(\w+)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

			        $last_offset = 0;
			        foreach ($matches as $match) {
			        	list($directive, $offset) = $match[0];

			        	// block before content
			        	if ($offset > $last_offset) {
							$part = substr($content, $last_offset, $offset - $last_offset);
			        		$this->currentSection .= $this->parseToken([$id, $part]);
			        	}
			        	$last_offset = $offset + strlen($directive);

			        	// directive
			        	debug_log("[[directive]]", $directive);
		        		$this->currentSection .= $this->parseToken([$id, $directive]);
			        }

			        // to EOF
			        $part = substr($content, $last_offset);
	        		$this->currentSection .= $this->parseToken([$id, $part]);
				}
		        else {
	        		$this->currentSection .= $content;
		        }
        	}
        	else {
        		$this->currentSection .= $token;
        	}
        }
    }

	protected function processExtends($view, array $data = [])
	{
		$this->extend = $view;
	}

	protected function processInclude($view, array $data = [])
	{
		// normalize $view

		// find file
		$path = app('view')->getFinder()->find($view);

		$this->expandFile($path, $data);
	}

	protected function processShow($section)
	{
		return array_get($this->sections, $section);
	}

    protected function startSection($name = null)
    {
    	array_push($this->sectionStack, [
    		$this->currentSectionName,
    		$this->currentSection,
    	]);

		$this->currentSectionName = $name;
		$this->currentSection = '';
    }

    protected function stopSection($action)
    {
    	$section_name = $this->currentSectionName;

    	if (isset($this->sections[$section_name])) {
    		switch ($action) {
   			case 'end':
   			case 'overwrite':
	            $this->sections[$section_name] = $this->currentSection;
    			break;

   			case 'prepend':
	            $this->sections[$section_name] = $this->currentSection . PHP_EOL . $this->sections[$section_name];
    			break;

   			case 'append':
	            $this->sections[$section_name] .= $this->currentSection;
    			break;
    		}
    	}
    	else {
    		// always create new section.
            $this->sections[$section_name] = $this->currentSection;
    	}

    	list($this->currentSectionName, $this->currentSection) = array_pop($this->sectionStack);
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
    	return eval("return \$this->processShow{$expression};");
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compileShow($expression)
    {
    	$section_name = $this->currentSectionName;
    	debug_log('compileShow', $section_name);
    	$this->stopSection('prepend');
    	return $this->processShow($section_name);
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
    	eval("\$this->startSection{$expression};");
    }

    /**
     * Compile the append statements into valid PHP.
     *
     * @param  string  $expression
     * @return null
     */
    protected function compileAppend($expression)
    {
    	debug_log('compileAppend');
    	$this->stopSection('append');
    }

    /**
     * Compile the end-section statements into valid PHP.
     *
     * @param  string  $expression
     * @return null
     */
    protected function compileEndsection($expression)
    {
    	debug_log('compileEndsection');
    	$this->stopSection('end');
    }

    /**
     * Compile the stop statements into valid PHP.
     *
     * @param  string  $expression
     * @return null
     */
    protected function compileStop($expression)
    {
    	debug_log('compileStop');
    	$this->stopSection('end');
    }

    /**
     * Compile the overwrite statements into valid PHP.
     *
     * @param  string  $expression
     * @return null
     */
    protected function compileOverwrite($expression)
    {
    	debug_log('compileOverwrite');
    	$this->stopSection('overwrite');
    }

    /**
     * Compile the extends statements into valid PHP.
     *
     * @param  string  $expression
     * @return null
     */
    protected function compileExtends($expression)
    {
    	debug_log('compileExtends');
    	eval("\$this->processExtends{$expression};");
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @param  string  $expression
     * @return null
     */
    protected function compileInclude($expression)
    {
    	debug_log('compileInclude');
    	eval("\$this->processInclude{$expression};");
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
    	return eval("return \$this->processShow{$expression};");
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
    	eval("\$this->startSection{$expression};");
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
    	$this->stopSection('append');
    }

}
