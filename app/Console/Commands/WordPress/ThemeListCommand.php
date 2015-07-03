<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ThemeListCommand extends Command
{
    use EnvironmentTrait;
    use \App\Services\WordPressService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'wordpress:theme:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List wordpress themes.';

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
        $this->runTemplateBootstrapScript();
//        $this->prepare();

        // title
        $this->info('-- WordPress Theme List --');

        // process
        $themes = wp_get_themes();

        foreach ($themes as $directory => $theme) {
            $mark = (get_template() === $directory) ? '*' : ' ';
            $this->line("{$mark} {$theme->name} [{$theme->version}] '{$directory}'");
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
