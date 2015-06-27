<?php

namespace App\Console\Commands\WordPress;

class DBMultisiteInstallCommand extends DBMultisiteAbstractCommand
{
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
        $this->prepare();

        if (wordpress_multisite_installed()) {
            $this->info('Multisite already installed.');

            return;
        }

        $this->install_multisite();

        $domain = parse_url(get_option('siteurl'), PHP_URL_HOST);
        $path = parse_url(trailingslashit(get_option('home')), PHP_URL_PATH);
        $subdomain_install = true;
        $sitename = wp_unslash(get_option('blogname'));
        $email = sanitize_email(get_option('admin_email'));

        $this->line('domain: '.$domain);
        $this->line('path: '.$path);
        $this->line('type: '.($subdomain_install ? 'subdomain' : 'subdirectory'));
        $this->line('sitename: '.$sitename);
        $this->line('email: '.$email);

        if (!$this->confirm('Site Install?')) {
            return;
        }

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

        $this->info('Done.');
    }

    protected function install_multisite()
    {
        global $wpdb;

        // We need to create references to ms global tables to enable Network.
        foreach ($wpdb->tables('ms_global') as $table => $prefixed_table) {
            $wpdb->$table = $prefixed_table;
        }

        // Create network tables.
        require_once wordpress_path('wp-admin/includes/upgrade.php');
        install_network();
    }
}
