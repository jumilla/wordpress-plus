<?php namespace App\Http\Controllers\WordPress;

use App\Http\Controllers\Controller as AppController;

/**
 *
 */
abstract class Controller extends AppController
{

	protected function runScript($path, array $globals = [])
	{
		foreach ($globals as $global) {
			global ${$global};
		}

		require wordpress_path($path);
	}

}
