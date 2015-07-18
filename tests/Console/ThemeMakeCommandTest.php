<?php

use Mockery as m;

class ThemeMakeCommandTest extends ConsoleCommandTestCase
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
        $filesystem->shouldReceive('put')->with('foo/index.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/style.css', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/screenshot.png', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/functions.php', m::any())->andReturn(true)
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
        $filesystem->shouldReceive('put')->with('foo/index.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/style.css', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/screenshot.png', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/functions.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/blade/index.blade.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/blade/layout.blade.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/classes/.gitkeep', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/languages/en/messages.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/languages/ja/messages.php', m::any())->andReturn(true)
            ->once();

        $this->runMakeCommand('simple', $filesystem);
    }

    public function testRunWithOptionSkeleton_Bootstrap()
    {
        $this->putenv('APP_LOCALE', 'ja');

        $filesystem = m::mock('Illuminate\Contracts\Filesystem\Filesystem');

        $filesystem->shouldReceive('createLocalDriver')->with(m::any())->andReturn($filesystem);
        $filesystem->shouldReceive('exists')->with('foo')->andReturn(false)
            ->once();
        $filesystem->shouldReceive('makeDirectory')->with('foo')->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/index.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/style.css', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/screenshot.png', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/functions.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/blade/index.blade.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/blade/layout.blade.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/classes/.gitkeep', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/languages/en/messages.php', m::any())->andReturn(true)
            ->once();
        $filesystem->shouldReceive('put')->with('foo/languages/ja/messages.php', m::any())->andReturn(true)
            ->once();

        $this->runMakeCommand('bootstrap', $filesystem);
    }

    protected function runMakeCommand($skeleton, $filesystem)
    {
        app()->instance('filesystem', $filesystem);

        $command = new App\Console\Commands\WordPress\ThemeMakeCommand();

        $this->runCommand($command, [
            'name' => 'foo', 'skeleton' => $skeleton,
        ]);
    }
}
