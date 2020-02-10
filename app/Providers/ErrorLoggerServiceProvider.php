<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ErrorLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'App\Services\Loggers\ErrorLogger\ErrorLoggerContract',
            'App\Services\Loggers\ErrorLogger\ErrorLogger'
        );
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
