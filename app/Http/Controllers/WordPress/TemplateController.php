<?php namespace App\Http\Controllers\WordPress;

/**
 *
 */
class TemplateController extends Controller
{

	public function __construct()
	{
		$this->middleware('wordpress.template_environment_setup');
	}

	public function provide()
	{
		/**
		 * Tells WordPress to load the WordPress theme and output it.
		 *
		 * @var bool
		 */
		define('WP_USE_THEMES', true);

//		/** Loads the WordPress Environment */
//		require wordpress_path('wp-load.php');

		wp();

		/** Loads the WordPress Template */
		require wordpress_path('wp-includes/template-loader.php');
	}

}
