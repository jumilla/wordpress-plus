<?php

namespace App\Http\Controllers\WordPress;

use Illuminate\Http\Request;

/**
 *
 */
class FileProvideController extends Controller
{
    public function download(Request $request)
    {
        info('Download: '.$request->path());

        $path = wordpress_path($request->path());

        // ERROR: file not found
        if (!is_file($path)) {
            abort(404);
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // ERROR: file extension is .php
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

        // TODO: and more...
        }
    }

    public function loadStyles(Request $request)
    {
        info('Load styles: '.$request->getQueryString());

        $this->runScript('wp-admin/load-styles.php');
    }

    public function loadScripts(Request $request)
    {
        info('Load script: '.$request->getQueryString());

        $this->runScript('wp-admin/load-scripts.php');
    }
}
