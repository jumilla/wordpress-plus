<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Support\Facades\Schema;

class DBMultisiteUninstallCommand extends DBMultisiteAbstractCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wpdb:multisite:uninstall';

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
        $this->prepare();

        global $wpdb;

        // We need to create references to ms global tables to enable Network.
        foreach ($wpdb->tables('ms_global') as $table => $prefixed_table) {
            $this->line('Dropping table: '.$prefixed_table);
            Schema::dropIfExists($prefixed_table);
        }

        $this->info('Done.');
    }
}
