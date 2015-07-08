<?php

use Mockery as m;

class ThemeMakeCommandTest extends ConsoleCommandTestCase
{
    public function testRun()
    {
        $storage = m::mock('App\Console\Commands\WordPress\Storage');
        $storage->shouldReceive('exists')->with('foo')->andReturn(false)
            ->once();
        $storage->shouldReceive('directory')->with('foo', m::any())
            ->once();

        $command = new App\Console\Commands\WordPress\PluginMakeCommand($storage);
        $command->setLaravel(new Laravel\Lumen\Application);
        $this->runCommand($command, [
            'name' => 'foo', 'skeleton' => 'minimum',
        ]);
    }
}
