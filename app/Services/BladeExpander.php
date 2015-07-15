<?php

namespace App\Services;

use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ViewFinderInterface;

class BladeExpander extends BladeCompiler
{
    const SECTION_EXTEND = 1;    /* allow @parent */
    const SECTION_OVERWRITE = 2;
    const SECTION_PREPEND = 3;
    const SECTION_APPEND = 4;

    const BLOCK_CONTENT = 1;
    const BLOCK_SECTION = 2;

    /**
     * @var \Illuminate\View\ViewFinderInterface
     */
    protected $finder;

    /**
     * @var array
     */
    protected $sections;

    /**
     * @var string
     */
    protected $currentSection;

    /**
     * @var string
     */
    protected $currentBuffer;

    /**
     * @var array
     */
    protected $contextStack;

    /**
     * @var array
     */
    protected $blockStack;

    public function __construct(ViewFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    public function expand($path)
    {
        $this->initializeContext();

        $this->expandFile($path);

        $this->pushBlockContent();

        return $this->combineBlocks();
    }

    protected function initializeContext()
    {
        // initialize work context
        $this->sections = [];
        $currentSection = null;
        $currentBuffer = '';
        $this->contextStack = [];
        $this->blockStack = [];
    }

    protected function expandFile($path)
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
                            $this->currentBuffer .= $this->parseToken([$id, $part]);
                        }
                        $last_offset = $offset + strlen($directive);

                        // directive
//			        	debug_log("[[directive]]", $directive);
                        $this->currentBuffer .= $this->parseToken([$id, $directive]);
                    }

                    // to EOF
                    $part = substr($content, $last_offset);
                    $this->currentBuffer .= $this->parseToken([$id, $part]);
                } else {
                    $this->currentBuffer .= $content;
                }
            } else {
                $this->currentBuffer .= $token;
            }
        }
    }

    protected function enterSection($section = null)
    {
        array_push($this->contextStack, [
            $this->currentSection,
            $this->currentBuffer,
        ]);

        $this->currentSection = $section;
        $this->currentBuffer = '';
    }

    protected function leaveSection($action)
    {
        debug_log('leaveSection', $action);
        $section = $this->currentSection;

        if (isset($this->sections[$section])) {
            switch ($action) {
            case static::SECTION_EXTEND:
                $this->sections[$section] = str_replace('@parent', $this->sections[$section], $this->currentBuffer);
                break;

            case static::SECTION_OVERWRITE:
                $this->sections[$section] = $this->currentBuffer;
                break;

            case static::SECTION_PREPEND:
                $this->sections[$section] = $this->currentBuffer.PHP_EOL.$this->sections[$section];
                break;

            case static::SECTION_APPEND:
                $this->sections[$section] = $this->sections[$section].PHP_EOL.$this->currentBuffer;
                break;
            }
        } else {
            // always create new section.
            $this->sections[$section] = $this->currentBuffer;
        }

        list($this->currentSection, $this->currentBuffer) = array_pop($this->contextStack);
    }

    protected function pushBlockContent()
    {
        $this->blockStack[] = [
            static::BLOCK_CONTENT,
            $this->currentBuffer,
        ];
        $this->currentBuffer = '';
    }

    protected function pushBlockSection($section)
    {
        $this->pushBlockContent();

        $this->blockStack[] = [
            static::BLOCK_SECTION,
            $section,
        ];
    }

    protected function combineBlocks()
    {
        $result = '';

        foreach ($this->blockStack as $block) {
            list($type, $value) = $block;

            switch ($type) {
            case static::BLOCK_CONTENT:
                $result .= $value;
                break;

            case static::BLOCK_SECTION:
                $result .= array_get($this->sections, $value);
                break;
            }
        }

        return $result;
    }

    /**
     * Compile the extends statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileExtends($expression)
    {
        eval("\$this->doInclude{$expression};");
    }

    /**
     * Compile the include statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileInclude($expression)
    {
        eval("\$this->doInclude{$expression};");
    }

    /**
     * @param  string  $view
     * @param  array  $data
     * @return null
     */
    protected function doInclude($view, array $data = [])
    {
        // normalize $view

        // find file
        $path = $this->finder->find($view);

        $this->expandFile($path);
    }

    /**
     * Compile the each statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileEach($expression)
    {
        eval("\$this->doEach{$expression};");
    }

    /**
     * Get the rendered contents of a partial from a loop.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  string  $iterator
     * @param  string  $empty
     * @return null
     */
    protected function doEach($view, $data, $iterator, $empty = 'raw|')
    {
        // If is actually data in the array, we will loop through the data and append
        // an instance of the partial view to the final result HTML passing in the
        // iterated value of this data array, allowing the views to access them.
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $data = ['key' => $key, $iterator => $value];

                $this->doInclude($view, $data);
            }
        }

        // If there is no data in the array, we will render the contents of the empty
        // view. Alternatively, the "empty view" could be a raw string that begins
        // with "raw|" for convenience and to let this know that it is a string.
        else {
            if (starts_with($empty, 'raw|')) {
                $this->currentBuffer .= substr($empty, 4);
            } else {
                $this->doInclude($empty);
            }
        }
    }

    /**
     * Compile the section statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return string
     */
    protected function compileSection($expression)
    {
        eval("\$this->enterSection{$expression};");
    }

    /**
     * Compile the end-section statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileEndsection($expression)
    {
        $this->leaveSection(static::SECTION_EXTEND);
    }

    /**
     * Compile the stop statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileStop($expression)
    {
        $this->leaveSection(static::SECTION_EXTEND);
    }

    /**
     * Compile the overwrite statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileOverwrite($expression)
    {
        $this->leaveSection(static::SECTION_OVERWRITE);
    }

    /**
     * Compile the append statements into valid PHP.
     *
     * @param  string  $expression
     * @return null
     */
    protected function compilePrepend($expression)
    {
        $this->leaveSection(static::SECTION_PREPEND);
    }

    /**
     * Compile the append statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileAppend($expression)
    {
        $this->leaveSection(static::SECTION_APPEND);
    }

    /**
     * Compile the show statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return string
     */
    protected function compileShow($expression)
    {
        $section = $this->currentSection;
        $this->leaveSection(static::SECTION_EXTEND);
        $this->pushBlockSection($section);
    }

    /**
     * Compile the yield statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return string
     */
    protected function compileYield($expression)
    {
        return eval("return \$this->pushBlockSection{$expression};");
    }

    /**
     * Compile the push statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compilePush($expression)
    {
        eval("\$this->enterSection{$expression};");
    }

    /**
     * Compile the endpush statements into valid PHP.
     *
     * @override
     * @param  string  $expression
     * @return null
     */
    protected function compileEndpush($expression)
    {
        $this->leaveSection('append');
    }

    /**
     * Compile the stack statements into the content.
     *
     * @override
     * @param  string  $expression
     * @return string
     */
    protected function compileStack($expression)
    {
        return eval("return \$this->pushBlockSection{$expression};");
    }

    /**
     * Get the path to the compiled version of a view.
     *
     * @override
     * @param  string  $path
     * @return string
     */
    public function getCompiledPath($path)
    {
        return $path;
    }

    /**
     * Determine if the view at the given path is expired.
     *
     * @override
     * @param  string  $path
     * @return bool
     */
    public function isExpired($path)
    {
        return false;
    }

    /**
     * Compile the view at the given path.
     *
     * @override
     * @param  string  $path
     * @return null
     */
    public function compile($path = null)
    {
        // nothing todo
    }
}
