<?php

namespace App\Database\Migrations;

use Jumilla\Versionia\Laravel\Support\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class App_1_0 extends Migration
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
            'framework' => 1.0,
        ];
    }

    /**
     * Upgrade database.
     *
     * @return void
     */
    public function up()
    {
        // $this->createSamplesTable();
    }

    /**
     * Downgrade database.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('samples');
    }

    /**
     * Create 'samples' table.
     *
     * @return void
     */
    protected function createSamplesTable()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }
}
