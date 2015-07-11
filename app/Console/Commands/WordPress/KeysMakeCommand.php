<?php

namespace App\Console\Commands\WordPress;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class KeysMakeCommand extends AbstractMakeCommand
{
    use EnvironmentTrait;
    use \App\Services\WordPressService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make wordpress keys.';

    /**
     * The console command description.
     *
     * @var array
     */
    protected $password_schemas = [
        'AUTH',
        'SECURE_AUTH',
        'LOGGED_IN',
        'NONCE',
    ];

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
        $this->runAdminBootstrapScript();

        // process
        $this->line(sprintf('APP_KEY=%s', $this->generateLaravelPassword()));
        $this->line('');

        foreach ($this->password_schemas as $schema) {
            $this->line(sprintf('WP_%s_KEY=%s', $schema, $this->generateWordPressPassword()));
            $this->line(sprintf('WP_%s_SALT=%s', $schema, $this->generateWordPressPassword()));
        }
        $this->line('');
    }

    /**
     * Generate password for WordPress.
     *
     * @return mixed
     */
    protected function generateLaravelPassword()
    {
        $cipher = config('app.cipher');

        if ($cipher === 'AES-128-CBC') {
            return str_random(16);
        } else {
            return str_random(32);
        }
    }

    /**
     * Generate password for WordPress.
     *
     * @return mixed
     */
    protected function generateWordPressPassword()
    {
        return wp_generate_password(64, true, true);
    }
}
