<?php

return [
    'disabled_feature' => [
        'page_title' => '[WordPress+] This feature is turned off',
        'setup_config' => [
            'title' => '[WordPress+] This feature is turned off',
            'message' => 'Please use the "wp-config.php" of WordPress+.',
            'body' => '
Please use the **"wp-config.php"** of WordPress+.

If you accidentally delete the **["wp-config.php"](https://github.com/jumilla/wordpress-plus/tree/master/wordpress/wp-config.php)** file, use in the following URL.
https://raw.githubusercontent.com/jumilla/wordpress-plus/master/wordpress/wp-config.php
            ',
        ],
    ],
    'bug_report' => [
        'page_title' => 'WordPress+ Error report',
        'header_title' => 'Error occurred on WordPress+',
        'header_message' => 'Please cooperate in a report transmission for bug fix.',
        'send_button' => 'GO-TO error report form',
    ],
];
