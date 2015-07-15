<?php

use Mockery as m;

class PluginMakeCommandTest extends ConsoleCommandTestCase
{
    public function testRunWithOptionSkeleton_None()
    {
        //        $this->runMakeCommand('');
    }

    public function testRunWithOptionSkeleton_Minimum()
    {
        $filesystem = m::mock('Illuminate\Contracts\Filesystem\Filesystem');

        $filesystem->shouldReceive('createLocalDriver')->with(m::any())->andReturn($filesystem);
        $filesystem->shouldReceive('exists')->with('foo')->andReturn(false)
            ->once();
        $filesystem->shouldReceive('makeDirectory')->with('foo')->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/foo.php', m::any())->andReturn(true)
            ->once();

        $this->runMakeCommand('minimum', $filesystem);
    }

    public function testRunWithOptionSkeleton_Simple()
    {
        $this->putenv('APP_LOCALE', 'ja');

        $filesystem = m::mock('Illuminate\Contracts\Filesystem\Filesystem');

        $filesystem->shouldReceive('createLocalDriver')->with(m::any())->andReturn($filesystem);
        $filesystem->shouldReceive('exists')->with('foo')->andReturn(false)
            ->once();
        $filesystem->shouldReceive('makeDirectory')->with('foo')->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/foo.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/classes/.gitkeep', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/languages/en/messages.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/languages/ja/messages.php', m::any())->andReturn(true)
            ->once();

        $this->runMakeCommand('simple', $filesystem);
    }

    protected function runMakeCommand($skeleton, $filesystem)
    {
        app()->instance('filesystem', $filesystem);

        $command = new App\Console\Commands\WordPress\PluginMakeCommand();

        $this->runCommand($command, [
            'name' => 'foo', 'skeleton' => $skeleton,
        ]);
    }
}
