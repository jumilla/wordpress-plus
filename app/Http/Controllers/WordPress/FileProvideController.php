<?php

namespace App\Http\Controllers\WordPress;

use Illuminate\Http\Request;

/**
 *
 */
class FileProvideController extends Controller
{
    public function downloadOnBackend(Request $request)
    {
        return $this->download($request, config('wordpress.url.backend_prefix'));
    }

    public function downloadOnSite(Request $request)
    {
        return $this->download($request, config('wordpress.url.site_prefix'));
    }

    protected function download(Request $request, $prefix)
    {
        $path = $request->path();

        // trim prefix
        if (starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix));
        }

        info('Download: '.$path);

        // make absolute file path
        $path = wordpress_path($path);

        // ERROR: file not found
        if (!is_file($path)) {
            abort(404);
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // ERROR: file extension is .php
        if ($extension == 'php') {
            abort(404);
        }

        info('Content-Type: '.$this->getMimeType($extension));

        return response()->download($path, null, [
            'Content-Type' => $this->getMimeType($extension),
        ]);
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
        default:
            return 'text/html';
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
