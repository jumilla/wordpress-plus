<?php

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * @return null
     */
    public function setUp()
    {
        WP_Mock::setUp();
    }

    /**
     * @return null
     */
    public function tearDown()
    {
        WP_Mock::tearDown();
        Mockery::close();
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
