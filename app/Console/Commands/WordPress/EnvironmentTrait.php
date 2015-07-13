<?php

namespace App\Console\Commands\WordPress;

trait EnvironmentTrait
{
    protected function logo()
    {
        require wordpress_path('wp-includes/version.php');

        $this->info('-*- WordPress+ -*-');
        $this->line('  <comment>WordPress:</comment> '.$wp_version);
        $this->line('  <comment>Laravel Framework:</comment> '.app()->version());
        $this->info('-*-*-*-*-*-*-*--*-');
        $this->line('');
    }

    protected function singlesiteTables($blog_id = 0)
    {
        global $wpdb;

        return $wpdb->tables('all', true, $blog_id);
    }

    protected function multisiteTables($blog_id = 0)
    {
        global $wpdb;

        return $wpdb->tables('ms_global', true, $blog_id);
    }

    protected function bootstrapForSinglesiteSetup()
    {
        define('WP_INSTALLING', true);

        global $wpdb;

        require_once wordpress_path('wp-config.php');
    }

    protected function bootstrapForMultisiteSetup()
    {
        define('WP_INSTALLING', true);
        define('WP_REPAIRING', true);

        global $wpdb;

        require_once wordpress_path('wp-config.php');
    }
}
