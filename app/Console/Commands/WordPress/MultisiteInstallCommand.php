<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;

class MultisiteInstallCommand extends Command
{
    use EnvironmentTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wordpress:multisite:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install wordpress multisite tables.';

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
        $this->bootstrapForMultisiteSetup();

        // title
        $this->info('-- WordPress Multisite Install --');

        // check
        if (wordpress_multisite_installed()) {
            $this->info('Multisite already installed.');

            return;
        }

        // gather
        $config = $this->gather();

        // deside
        if (!$this->deside($config)) {
            // cancel
            return;
        }

        // process
        $this->process($config);

        // done
        $this->line('Done.');
    }

    protected function gather()
    {
        $config = [];
        $config['domain'] = parse_url(get_option('siteurl'), PHP_URL_HOST);
        $config['path'] = parse_url(trailingslashit(get_option('home')), PHP_URL_PATH);
        $config['subdomain_install'] = true;
        $config['sitename'] = wp_unslash(get_option('blogname'));
        $config['email'] = sanitize_email(get_option('admin_email'));

        return $config;
    }

    protected function deside($config)
    {
        extract($config);

        $this->line('domain: '.$domain);
        $this->line('path: '.$path);
        $this->line('type: '.($subdomain_install ? 'subdomain' : 'subdirectory'));
        $this->line('sitename: '.$sitename);
        $this->line('email: '.$email);

        return $this->confirm('Are you sure?', true);
    }

    protected function process(array $config)
    {
        extract($config);

        $this->createMultisiteTables();

        if (!$this->createSite($domain, $path, $subdomain_install, $sitename, $email)) {
            // error
            return;
        }
    }

    protected function createMultisiteTables()
    {
        global $wpdb;

        // We need to create references to ms global tables to enable Network.
        foreach ($this->multisiteTables() as $table => $prefixed_table) {
            $wpdb->{$table} = $prefixed_table;
        }

        // Create network tables.
        require_once wordpress_path('wp-admin/includes/upgrade.php');
        install_network();
    }

    protected function createSite($domain, $path, $subdomain_install, $sitename, $email)
    {
        $result = populate_network(1, $domain, $email, $sitename, $path, $subdomain_install);

        if (is_wp_error($result)) {
            if ($result->get_error_code() !== 'no_wildcard_dns') {
                // Error!
                foreach ($result->get_error_codes() as $error_code) {
                    $this->error($error_code);
                }

                return;
            }

            $this->line('Warning: no_wildcard_dns');
        }
    }
}
