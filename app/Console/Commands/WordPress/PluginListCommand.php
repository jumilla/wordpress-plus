<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PluginListCommand extends Command
{
    use EnvironmentTrait;
    use \App\Services\WordPressService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wordpress:plugin:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List wordpress plugins.';

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
//        $this->prepare();

        // title
        $this->info('-- WordPress Plugin List --');

        // process
        $plugins = get_plugins();

        foreach ($plugins as $directory => $plugin) {
            $this->line("  {$plugin['Name']} [{$plugin['Version']}] '{$directory}'");
        }
        $this->line('');
    }

    /**
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }
}
