<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Output\ConsoleOutput as SymfonyConsoleOutput;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class PHP7ServiceProvider extends ServiceProvider
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
                call_user_func($this->frameworkUncaughtExceptionHandler, $e);
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
}
