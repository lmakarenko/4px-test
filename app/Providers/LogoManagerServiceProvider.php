<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use App\Services\LogoManager\LogoManager;

class LogoManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\LogoManager\LogoManagerContract', function($app) {
                return new LogoManager(
                    Storage::disk('logo'),
                    $app->make('App\Services\Loggers\ErrorLogger\ErrorLoggerContract')
                );
        });
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
