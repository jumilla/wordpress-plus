<?php

namespace App\Http\Controllers\WordPress;

use App\Http\Controllers\Controller as AppController;

/**
 *
 */
abstract class Controller extends AppController
{
    use \App\Services\WordPressService;
}
