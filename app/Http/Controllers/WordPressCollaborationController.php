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
		// これを定義しないと以下のエラーが出る
		// XMLRPC -32601: requested method jetpack.verifyRegistration does not exist
		define('XMLRPC_REQUEST', true);

		// MEMO PHP7で、apply_filters() がないと言われる対策。
		require_once( base_path('wordpress/wp-config.php') );
//		info (function_exists('apply_filters'));

		// TODO xmlrpc.phpは書き直した方がいい
		require base_path('wordpress/xmlrpc.php');
	}

}
