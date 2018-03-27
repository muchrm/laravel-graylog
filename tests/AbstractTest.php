<?php

abstract class AbstractTest extends Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Import default settings
        $defaultGraylogSettings = require __DIR__.'/../config/graylog.php';
        $app['config']->set('graylog', $defaultGraylogSettings);
    }

    protected function getPackageProviders($app)
    {
        return ['Muchrm\Graylog\GraylogServiceProvider'];
    }
}
