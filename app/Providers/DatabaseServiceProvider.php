<?php

namespace App\Providers;

use Jumilla\Versionia\Laravel\Support\DatabaseServiceProvider as ServiceProvider;
use App\Database\Migrations;
use App\Database\Seeds;

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
            '1.0' => Migrations\Framework_1_0::class,
        ]);

        $this->migrations('app', [
            '1.0' => Migrations\App_1_0::class,
        ]);

        $this->seeds([
            'test' => Seeds\Test::class,
            'production' => Seeds\Production::class,
        ], 'test');
    }
}
