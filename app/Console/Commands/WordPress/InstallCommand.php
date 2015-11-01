<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use EnvironmentTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wordpress:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install wordpress tables.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // entrance
        $this->logo();

        // prepare
        $this->bootstrapForSinglesiteSetup();

        // title
        $this->info('-- WordPress Install --');

        // check
        if (wordpress_installed()) {
            $this->error('Already installed.');

            return;
        }

        // gather
        $config = $this->gather();

        // deside
        if (!$this->deside()) {
            // cancel
            $this->line('Canceled.');

            return;
        }

        // process
        $this->process($config);

        // done
        $this->line('Done.');
    }

    protected function gather()
    {
        app()->configure('app');

        $config = [
            'language' => config('app.locale'),
            'backend_url' => env('WP_BACKENDURL'),
            'site_url' => env('WP_SITEURL'),
            'site_title' => '',
            'site_description' => 'This is a website.',
            'site_public' => false,
            'admin_username' => '',
            'admin_password' => '',
            'admin_email' => '',
        ];

        $config['language'] = $this->ask('Language', $config['language']);
        if ($config['backend_url'] === null) {
            $config['backend_url'] = $this->ask('Backend URL', 'http://localhost:8000');
        } else {
            $this->info('Backend URL: '.$config['backend_url'].' (.env: WP_BACKENDURL)');
        }
        if ($config['site_url'] === null) {
            $config['site_url'] = $this->ask('Site URL', 'http://localhost:8000');
        } else {
            $this->info('Site URL: '.$config['site_url'].' (.env: WP_SITEURL)');
        }
        $config['site_title'] = $this->ask('Site title', $config['site_title']);
        $config['site_description'] = $this->ask('Site description', $config['site_description']);
        $config['site_public'] = $this->confirm('Site public', $config['site_public']);
        $config['admin_username'] = $this->ask('Admin username', $config['admin_username']);
        $config['admin_password'] = $this->ask('Admin password', $config['admin_password']);
        $config['admin_email'] = $this->ask('Admin email', $config['admin_email']);

        return $config;
    }

    protected function deside()
    {
        return $this->confirm('Are you sure?', true);
    }

    protected function process(array $config)
    {
        global $wpdb;

        // We need to create references to ms global tables to enable Network.
        foreach ($this->singlesiteTables() as $table => $prefixed_table) {
            $wpdb->{$table} = $prefixed_table;
        }

        require_once wordpress_path('wp-admin/includes/upgrade.php');
        $result = wp_install(
            $config['site_title'],
            $config['admin_username'],
            $config['admin_email'],
            $config['site_public'],
            '', // dummy
            wp_slash($config['admin_password']),
            $config['language']
        );

//        var_dump($result);

        update_option('siteurl', $config['backend_url']);
        update_option('home', $config['site_url']);
        update_option('blogdescription', $config['site_description']);

        if ($config['language'] == 'ja') {
            // 週の初めは '日曜日'
            update_option('start_of_week', '0');

            // 日付フォーマットは 'Y年n月j日'
            update_option('date_format', 'Y年n月j日');

            // 時刻フォーマットは 'H:i'
            update_option('time_format', 'H:i');
        }
    }
}
