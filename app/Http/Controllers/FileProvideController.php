<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileProvideController extends Controller
{

	public function download(Request $request)
	{
		info('Download: ' . $request->path());

		$path = base_path('wordpress/' . $request->path());
		$extension = pathinfo($path, PATHINFO_EXTENSION);

		if ($extension == 'php') {
			abort(404);
		}

		$headers = [];
		$headers['Content-Type'] = $this->getMimeType($extension);
		return response()->download($path, null, $headers);
	}

	private function getMimeType($extension)
	{
		switch ($extension) {
		case 'css':
			return 'text/css';
		case 'js':
			return 'application/javascript';
		case 'svg':
			return 'image/svg+xml';
		}
	}

	public function loadStyles(Request $request)
	{
		info('Load styles: ' . $request->getQueryString());

		$this->requireScript('wp-admin/load-styles.php');
	}

	public function loadScripts(Request $request)
	{
		info('Load script: ' . $request->getQueryString());

		$this->requireScript('wp-admin/load-scripts.php');
	}

}
