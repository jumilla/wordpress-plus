<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
	protected function requireScript($filename, array $globals = [])
	{
		foreach ($globals as $global) {
			global ${$global};
		}

		require base_path("wordpress/{$filename}");
	}

}
