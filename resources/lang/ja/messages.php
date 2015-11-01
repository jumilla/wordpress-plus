<?php

return [
    'disabled_feature' => [
        'page_title' => '[WordPress+] この機能はオフになっています',
        'setup_config' => [
            'title' => '[WordPress+] この機能はオフになっています',
            'message' => 'WordPress+の "wp-config.php" をお使いください。',
            'body' => '
WordPress+の **"wp-config.php"** をお使いください。

もし **["wp-config.php"](https://github.com/jumilla/wordpress-plus/tree/master/wordpress/wp-config.php)** ファイルを削除してしまった場合は、次のURLのファイルを配置してください。
https://raw.githubusercontent.com/jumilla/wordpress-plus/master/wordpress/wp-config.php
            ',
        ],
    ],
    'bug_report' => [
        'page_title' => 'WordPress+ エラー報告',
        'header_title' => 'WordPress+でエラー発生',
        'header_message' => '不具合修正のため、レポート送信にご協力ください。',
        'send_button' => 'エラーレポートフォームへ',
    ],
];
