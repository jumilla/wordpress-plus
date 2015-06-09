<?php namespace App\Http\Controllers;

class WordPressCollaborationController extends Controller
{

	public function __construct()
	{
	}

	public function trackback()
	{
		require base_path('wordpress/wp-trackback.php');
	}

	public function xmlrpc()
	{
//		info(app('request')->method());
//		info (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 'nothing');

//		info (function_exists('apply_filters'));
		require_once( base_path('wordpress/wp-config.php') );
//		info (function_exists('apply_filters'));

		require base_path('wordpress/xmlrpc.php');
	}

}
