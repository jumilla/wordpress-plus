<?php

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * @return null
     */
    public function setUp()
    {
    }

    /**
     * @return null
     */
    public function tearDown()
    {
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

    /**
     * Turn the given URI into a fully qualified URL.
     *
     * @override
     * @param  string  $uri
     * @return string
     */
    protected function prepareUrlForRequest($uri)
    {
        if (!parse_url($uri, PHP_URL_HOST)) {
            $_SERVER['REQUEST_URI'] = parse_url($uri, PHP_URL_PATH);
            $_SERVER['PHP_SELF'] = '/index.php'.parse_url($uri, PHP_URL_PATH);
        }

        return parent::prepareUrlForRequest($uri);
    }

    /**
     * @param string $name
     * @param string $value
     * @return bool
     */
    protected function putenv($name, $value)
    {
        return putenv("$name=$value");
    }

    /**
     * @param string $env_name
     * @param string $config_name
     * @param string $value
     * @return bool
     */
    protected function putenvAndConfig($env_name, $config_name, $value)
    {
        config()->set($config_name, $value);

        return putenv("$env_name=$value");
    }
}
