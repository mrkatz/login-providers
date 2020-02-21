<?php

namespace Mrkatz\LoginProviders;

use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Support\ServiceProvider;
use Mrkatz\LoginProviders\Commands\UserMakeCommand;
use Mrkatz\LoginProviders\Preset\LoginProviderPreset;
use Mrkatz\LoginProviders\Traits\Configable;

class LoginProvidersServiceProvider extends ServiceProvider
{
//    use Configable;
    protected $MIGRATIONS_PATH = __DIR__ . '/Database/Migrations';
    protected $CONFIG_PATH = __DIR__ . '/../config/login-providers.php';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
//            $this->commands([
//                UserMakeCommand::class,
//            ]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this
            ->registerRoutes()
            ->registerMigrations()
            ->registerConfig()
            ->registerPreset()
            ->registerViews();
    }

    /**
     * Register & Publish Views
     *
     * @return $this
     */
    protected function registerViews()
    {
//        $this->loadViewsFrom(realpath(__DIR__ . '/Views'), 'media-manager');
//
//        $this->publishes([
//            __DIR__ . '/Views' => resource_path('views/vendor/mrkatz/media-manager'),
//        ], 'views');

        return $this;
    }

    /**
     * Register Preset Command Micro
     *
     * @return $this
     */
    protected function registerPreset()
    {
//        PresetCommand::macro('login-providers', function ($command) {
//            LoginProviderPreset::install(true);
//            $command->info('Login Providers scaffolding installed successfully.');
//        });

        return $this;
    }

    /**
     * Register & Publish Config Files
     *
     * @return $this
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom($this->CONFIG_PATH, 'login-providers');

        $this->publishes([
                             __DIR__ . '/../config' => config_path(),
                         ], 'config');

        return $this;
    }

    /**
     * Publish Migrations
     *
     * @return $this
     */
    protected function registerMigrations()
    {
        $this->publishes([
                             $this->MIGRATIONS_PATH => database_path('migrations/'),
                         ], 'migrations');

        $this->loadMigrationsFrom($this->MIGRATIONS_PATH);

        return $this;
    }

    /**
     * Register Routes
     *
     * @return $this
     */
    protected function registerRoutes()
    {
//        $this->loadRoutesFrom(realpath(__DIR__ . '/Routes/routes.php'));

        return $this;
    }
}
