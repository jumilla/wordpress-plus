<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;

class DBStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wpdb:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup wordpress database.';

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
    }
}
