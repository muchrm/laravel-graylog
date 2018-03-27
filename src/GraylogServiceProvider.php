<?php

namespace Muchrm\Graylog;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class GraylogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/graylog.php' => $this->app->configPath().'/graylog.php',
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton('graylog', Graylog::class);

        // Register handler
        $monoLog = Log::getMonolog();
        $monoLog->pushHandler(new GraylogHandler());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['graylog'];
    }
}
