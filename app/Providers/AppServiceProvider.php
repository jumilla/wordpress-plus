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
    }
}
