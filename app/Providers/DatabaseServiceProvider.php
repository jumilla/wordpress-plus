<?php

namespace App\Providers;

use Jumilla\Versionia\Laravel\Support\DatabaseServiceProvider as ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap database services.
     *
     * @return void
     */
    public function boot()
    {
        $this->migrations('framework', [
            '1.0' => \App\Database\Migrations\Framework_1_0::class,
        ]);

/*
        $this->registerMigrations('app', [
            '1.0' => \App\Database\Migrations\App_1_0::class,
        ]);
*/
        $this->seeds([
            'test' => \App\Database\Seeds\Test::class,
            'production' => \App\Database\Seeds\Production::class,
        ], 'test');
    }
}
