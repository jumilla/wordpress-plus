<?php namespace App\Http\Controllers;

class WordPressContentsController extends Controller
{

	public function provide()
	{
		require base_path('wordpress/index.php');
	}

}
