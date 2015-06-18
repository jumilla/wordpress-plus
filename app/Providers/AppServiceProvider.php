<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
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
                }
                else {
                    (new SymfonyExceptionHandler(env('APP_DEBUG', false)))->createResponse($e)->send();
                }
            }
        });
    }
}
