<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;

class PluginMakeCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:plugin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make wordpress plugin.';

    protected $storage;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->storage = new Storage('wordpress/wp-content/plugins');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }

}
