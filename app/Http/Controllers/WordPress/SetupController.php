<?php

namespace App\Http\Controllers\WordPress;

/**
 *
 */
class SetupController extends Controller
{
    public function setupConfig()
    {
        $this->runAdminScript('setup-config.php');
    }

    public function setupInstall()
    {
        info('setupInstall');
        $this->runAdminScript('install.php');
    }
}
