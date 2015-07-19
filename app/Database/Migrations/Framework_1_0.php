<?php

namespace App\Database\Migrations;

use Jumilla\Versionia\Laravel\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Framework_1_0 extends Migration
{
    /**
     * Define depends migration versions.
     * ex) ['auth' => 1.0].
     *
     * @return array
     */
    public function dependsTo()
    {
        return [
        ];
    }

    /**
     * Upgrade database.
     *
     * @return void
     */
    public function up()
    {
        $this->createCacheTable();
        $this->createSessionsTable();
        $this->createJobsTable();
    }

    /**
     * Downgrade database.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache');
    }

    /**
     * Create 'cache' table.
     *
     * @return void
     */
    protected function createCacheTable()
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->text('value');
            $table->integer('expiration');
        });
    }

    /**
     * Create 'sessions' table.
     *
     * @return void
     */
    protected function createSessionsTable()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->text('payload');
            $table->integer('last_activity');
        });
    }

    /**
     * Create 'jobs' table.
     *
     * @return void
     */
    protected function createJobsTable()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue');
            $table->text('payload');
            $table->tinyInteger('attempts')->unsigned();
            $table->tinyInteger('reserved')->unsigned();
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }
}
