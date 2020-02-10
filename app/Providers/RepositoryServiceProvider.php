<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * Service Provider for all app's repositories
 *
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\User\UserContract',
            'App\Repositories\User\UserRepository'
        );
        $this->app->bind(
            'App\Repositories\Section\SectionContract',
            'App\Repositories\Section\SectionRepository'
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
