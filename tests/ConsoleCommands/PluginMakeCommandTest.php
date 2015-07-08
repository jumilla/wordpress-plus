<?php

use Mockery as m;

class PluginMakeCommandTest extends ConsoleCommandTestCase
{
    public function testRun()
    {
        $storage = m::mock('App\Console\Commands\WordPress\Storage');

        $command = new App\Console\Commands\WordPress\PluginMakeCommand($storage);
        $command->setLaravel(new Laravel\Lumen\Application);
        $storage->shouldReceive('exists')->with('foo')->andReturn(false)
            ->once();
        $storage->shouldReceive('directory')->with('foo', m::any())
            ->once();
        $this->runCommand($command, [
            'name' => 'foo', 'skeleton' => 'minimum',
        ]);
    }
}
