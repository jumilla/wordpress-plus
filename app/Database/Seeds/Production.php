<?php

namespace App\Database\Seeds;

use Jumilla\Versionia\Laravel\Support\Seeder;
use Illuminate\Database\Eloquent\Model;

class Production extends Seeder
{
    /**
     * Require versions.
     *
     * @return array
     */
    public function versions()
    {
        return [
            'app'  => '1.0',
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Model::reguard();
    }
}
