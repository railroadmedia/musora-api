<?php

namespace Railroad\MusoraApi\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class MusoraApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->publishes(
            [
                __DIR__ . '/../../config/musora-api.php' => config_path('musora-api.php'),
            ]
        );

        //load package routes file
        $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
