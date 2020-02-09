<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SimpleLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\Loggers\ErrorLoggerContract', 'App\Services\Loggers\SimpleLogger\SimpleLogger');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
