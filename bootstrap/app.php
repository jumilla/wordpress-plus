<?php

require_once __DIR__.'/../vendor/autoload.php';

Dotenv::load(__DIR__.'/../');

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

//$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     // Illuminate\Cookie\Middleware\EncryptCookies::class,
//     // Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//     // Illuminate\Session\Middleware\StartSession::class,
//     // Illuminate\View\Middleware\ShareErrorsFromSession::class,
//     // Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
// ]);

$app->routeMiddleware([
    'wordpress.blog_admin_bootstrap' => App\Http\Middleware\WordPress\BlogAdminBootstrapMiddleware::class,
    'wordpress.site_admin_bootstrap' => App\Http\Middleware\WordPress\SiteAdminBootstrapMiddleware::class,
    'wordpress.template_bootstrap' => App\Http\Middleware\WordPress\TemplateBootstrapMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(Jumilla\Versionia\Laravel\ServiceProvider::class);
//if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
if (starts_with(PHP_VERSION, '7.')) {
    $app->register(App\Providers\PHP7ServiceProvider::class);
}
$app->register(App\Providers\WordPressServiceProvider::class);
$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\DatabaseServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

if (env('APP_DEBUG')) {
    $app->register(Barryvdh\Debugbar\LumenServiceProvider::class);
    $app->configure('debugbar');
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

require __DIR__.'/../app/Http/routes.php';

return $app;
