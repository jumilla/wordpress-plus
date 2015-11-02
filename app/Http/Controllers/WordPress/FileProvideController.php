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

    protected function download(Request $request, $prefix, $attachment = false)
    {
        $path = $request->path();

        // trim prefix
        if (starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix));
        }

        debug_log('File Download[File Path]', $path);

        // make absolute file path
        $path = wordpress_path($path);

        // ERROR: file not found
        if (!is_file($path)) {
            debug_log('File Download: [Abort]: not found');
            abort(404);
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // ERROR: file extension is .php
        if ($extension == 'php') {
            debug_log('File Download: [Abort]: .php');
            abort(404);
        }

        debug_log('File Download[Content Type]', $this->getMimeType($path, $extension));

        $headers = [
            'Content-Type' => $this->getMimeType($path, $extension),
        ];

        if ($attachment === false) {
            return response()->make(file_get_contents($path), 200, $headers);
        }

        return response()->download($path, 200, $headers);
    }

    private function getMimeType($path, $extension)
    {
        switch ($extension) {
        case 'css':
            return 'text/css';
        case 'js':
            return 'application/javascript';
        case 'svg':
            return 'image/svg+xml';

        default:
            return app('files')->mimeType($path);
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
