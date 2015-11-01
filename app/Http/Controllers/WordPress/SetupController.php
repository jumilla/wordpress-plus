<?php

namespace App\Http\Controllers\WordPress;

use Parsedown;

/**
 *
 */
class SetupController extends Controller
{
    public function setupConfig()
    {
		$parsedown = new Parsedown();

    	$prefix = 'messages.disabled_feature.setup_config';

    	return view('disabled-feature', [
    		'title' => trans($prefix . '.title'),
    		'message' => trans($prefix . '.message'),
    		'body' => $parsedown->text(trans($prefix . '.body')),
    	]);
    }

    public function setupInstall()
    {
        info('setupInstall');
        $this->runAdminScript('install.php');
    }
}
