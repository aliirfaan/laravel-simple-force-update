<?php

namespace aliirfaan\LaravelSimpleForceUpdate;

use Illuminate\Support\ServiceProvider;
use aliirfaan\LaravelSimpleForceUpdate\Services\SimpleForceUpdateService;

class SimpleForceUpdateProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('aliirfaan\LaravelSimpleForceUpdate\Services\SimpleForceUpdateService', function ($app) {
            return new SimpleForceUpdateService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
