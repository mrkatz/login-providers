<?php

namespace Mrkatz\LoginProviders;

use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Support\ServiceProvider;
use Mrkatz\LoginProviders\Commands\UserMakeCommand;
use Mrkatz\LoginProviders\Preset\LoginProviderPreset;

class LoginProvidersServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '../config/loginproviders.php';
    const MIGRATIONS_PATH = __DIR__ . '/Database/Migrations';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UserMakeCommand::class,
            ]);
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
     * Register Preset Command Micro
     * @return $this
     */
    protected function registerPreset()
    {
        PresetCommand::macro('login-providers', function ($command) {
            LoginProviderPreset::install(true);
            $command->info('Login Providers scaffolding installed successfully.');
        });

        return $this;
    }

    /**
     * Register & Publish Config Files
     *
     * @return $this
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'mrkatz.login-providers');

        $this->publishes([
            __DIR__ . '/../config' => config_path('mrkatz'),
        ], 'config');

        return $this;
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
     * Register Routes
     *
     * @return $this
     */
    protected function registerRoutes()
    {
//        $this->loadRoutesFrom(realpath(__DIR__ . '/Routes/routes.php'));

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
            self::MIGRATIONS_PATH => database_path('migrations/'),
        ], 'migrations');

        $this->loadMigrationsFrom(self::MIGRATIONS_PATH);

        return $this;
    }
}
