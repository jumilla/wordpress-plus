<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;

class DBMultisiteAbstractCommand extends Command
{
    protected function prepare()
    {
        define('WP_INSTALLING', true);
        define('WP_REPAIRING', true);

        global $wpdb;

        require_once wordpress_path('wp-config.php');
    }
}
