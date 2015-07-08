<?php

use Mockery as m;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
	/**
	 * @return null
	 */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
