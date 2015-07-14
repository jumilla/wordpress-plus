<?php

return [
    'url' => [
        'backend' => rtrim(env('WP_BACKENDURL'), '/'),
        'site' => rtrim(env('WP_SITEURL'), '/'),
        'backend_prefix' => trim(parse_url(env('WP_BACKENDURL'), PHP_URL_PATH), '/').'/',
        'site_prefix' => trim(parse_url(env('WP_SITEURL'), PHP_URL_PATH), '/').'/',
    ],

    'themes' => [
        'blade' => [
            'directory' => 'blade',

            'header_comment' => 'This file made by Blade. Do not modified.',

            'precompile' => env('WP_BLADE_PRECOMPILE', true),
        ],

        'lang' => [
            'directory' => 'languages',
        ],
    ],

    'plugins' => [
        'lang' => [
            'directory' => 'languages',
        ],
    ],
];
