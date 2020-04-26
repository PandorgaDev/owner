<?php

namespace Pandorga\Owner;

use Illuminate\Support\ServiceProvider;

class OwnerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishResources();
        }

        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/owner.php', 'owner');
        }
    }

    public function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../config/owner.php' => config_path('owner.php')
        ], 'owner-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_owners_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_owners_table.php'),
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
