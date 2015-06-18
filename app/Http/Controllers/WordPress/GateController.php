<?php

namespace App\Http\Controllers\WordPress;

/**
 *
 */
class GateController extends Controller
{
    public function __construct()
    {
    }

    public function login()
    {
        $this->runScript('wp-login.php', [
            'user_login', 'error',
        ]);
    }
}
