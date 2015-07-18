<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ContentClassLoader;
use App\Services\BladeExpander;

class WordPressServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        ContentClassLoader::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->configure('wordpress');

        $this->setupBladeEnvironment($this->blade());

        app()->singleton('blade.expander', function () {
            $expander = new BladeExpander(app('view')->getFinder());

            $this->setupBladeEnvironment($expander);

            return $expander;
        });
    }

    /**
     * @return void
     */
    protected function blade()
    {
        return app('view')->getEngineResolver()->resolve('blade')->getCompiler();
    }

    /**
     * @return void
     */
    protected function setupBladeEnvironment($compiler)
    {
        $compiler->directive('filter', function ($expression) {
            return "<?php echo apply_filters{$expression}; ?>";
        });

        $compiler->directive('action', function ($expression) {
            return "<?php do_action{$expression}; ?>";
        });

        $compiler->directive('shortcode', function ($expression) {
            $expression = substr($expression, 1, strlen($expression) - 2);

            return "<?php echo do_shortcode('{$expression}'); ?>";
        });

        $postloop_counter = 0;

        $compiler->directive('postloop', function ($expression) use ($postloop_counter) {
            if (empty($expression) || $expression == '()') {
                $query = "\$GLOBALS['wp_query']";
            } else {
                $expression = substr($expression, 1, strlen($expression) - 2);
                $query = "new WP_Query({$expression})";
            }

            $postquery = sprintf('$__postquery_%d', ++$postloop_counter);

            $script = "<?php {$postquery} = {$query}; ?>\n";
            $script .= "<?php if ({$postquery}->have_posts()) : ?>\n";
            $script .= "\t<?php while ({$postquery}->have_posts()) : {$postquery}->the_post(); ?>";

            return $script;
        });

        $compiler->directive('postempty', function ($expression) {
            return "\t<?php endwhile; ?>\n<?php else : ?>";
        });

        $compiler->directive('endpostloop', function ($expression) {
            return '<?php endif; ?>';
        });
    }
}
