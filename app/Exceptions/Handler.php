<?php

namespace App\Exceptions;

use Exception;
use DateTime;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        // 405 Method not allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            $request = app('request');

            debug_log(get_class($e), $request->method().' '.$request->fullUrl());
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // 404 Not found
        if ($e instanceof NotFoundHttpException) {
            // backend only
            if (starts_with($request->path(), config('wordpress.url.backend_prefix'))) {
                return $this->showBugReportForm($e);
            }
        }
        // other
        else {
            return $this->showBugReportForm($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    protected function showBugReportForm(Exception $e)
    {
        $backtrace = $e->getTrace();

        foreach ($backtrace as &$trace) {
            if (isset($trace['file'])) {
                $trace['file'] = substr($trace['file'], strlen(base_path()) + 1);
            }
        }

        $source = $backtrace[0];

        $request = app('request');

        $data = [
            'occurred_at' => (new DateTime)->format(DateTime::RFC3339),
            'occurred_ip' => array_get($_SERVER, 'SERVER_ADDR', $_SERVER['REMOTE_ADDR']),
            'occurred_platform' => [
                's' => php_uname('s'),
                'n' => php_uname('n'),
                'r' => php_uname('r'),
                'v' => php_uname('v'),
                'm' => php_uname('m'),
            ],
            'occurred_runtime' => 'PHP',
            'occurred_runtime_version' => PHP_VERSION,
            'occurred_context' => [
                'request' => [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'headers' => $request->header(),
                ],
            ],
            'application' => 'WordPress+',
            'build_version' => WORDPRESS_PLUS_VERSION,
            'build_signature' => date('Ymd-His', filemtime(base_path('app/version.php'))),
            'source_file' => substr($e->getFile(), strlen(base_path()) + 1),
            'source_line' => $e->getLine(),
            'source_class' => array_get($source, 'class', null),
            'source_function' => array_get($source, 'function', null),
            'exception_type' => 'fatal',
            'exception_class' => get_class($e),
            'exception_message' => $e->getMessage(),
            'exception_backtrace' => $backtrace,
        ];

        return view('bug-report', $data);
    }
}
