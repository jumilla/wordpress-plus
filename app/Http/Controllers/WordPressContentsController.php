<?php namespace App\Http\Controllers;

class WordPressContentsController extends Controller
{

	public function provide()
	{
		/**
		 * Tells WordPress to load the WordPress theme and output it.
		 *
		 * @var bool
		 */
		define('WP_USE_THEMES', true);

		/** Loads the WordPress Environment */
		require base_path('wordpress/wp-load.php');

		wp();

		/** Loads the WordPress Template */
		require base_path('wordpress/wp-includes/template-loader.php');
	}

}
