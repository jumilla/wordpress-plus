<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupErrorHandlers();
    }

    protected function setupErrorHandlers()
    {
        // Clear Lumen's error handler
        // MEMO Need for PHP7
        set_error_handler(function () {});

        $this->frameworkUncaughtExceptionHandler = set_exception_handler(function ($e) {
            if ($e instanceof \Exception) {
                $this->frameworkUncaughtExceptionHandler($e);
            }
            // PHP7
            else {
                $logger = app('Psr\Log\LoggerInterface');
                $logger->error($e->getMessage(), [$e]);
                $logger->debug($e->getTraceAsString());

                if (app()->runningInConsole()) {
                    (new SymfonyConsoleApplication)->renderException($e, new SymfonyConsoleOutput);
                } else {
                    (new SymfonyExceptionHandler(env('APP_DEBUG', false)))->createResponse($e)->send();
                }
            }
        });
    }

    public function boot()
    {
        $this->setupBladeEnvironment();
    }

    protected function blade()
    {
        return app('view')->getEngineResolver()->resolve('blade')->getCompiler();
    }

    protected function setupBladeEnvironment()
    {
        $blade = $this->blade();

        $blade->directive('filter', function ($expression) {
            return "<?php echo apply_filters{$expression}; ?>";
        });

        $blade->directive('action', function ($expression) {
            return "<?php do_action{$expression}; ?>";
        });

        $blade->directive('shortcode', function ($expression) {
            $expression = substr($expression, 1, strlen($expression) - 2);

            return "<?php echo do_shortcode('{$expression}'); ?>";
        });

        $blade->directive('postloop', function ($posts) {
            if (empty($posts) || $posts == '()') {
                $posts = '$GLOBALS[\'wp_query\']';
            }
            else {
                $posts = substr($posts, 1, strlen($posts) - 2);
            }
            return "<?php while ({$posts}->have_posts()) : {$posts}->the_post(); ?>";
        });

        $blade->directive('endpostloop', function ($expression) {
            return '<?php endwhile; wp_reset_query(); ?>';
        });
    }
}
