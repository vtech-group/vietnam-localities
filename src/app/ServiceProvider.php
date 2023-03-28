<?php

namespace Vtech\VietnamLocalities;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Vtech\VietnamLocalities\Console\Commands\VietnamLocalitiesImport;

/**
 * The VietnamLocalitiesServiceProvider class.
 *
 * @package vtech/vietnam-localities
 * @author  Jackie Do <anhvudo@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'VietnamLocalitiesImport' => VietnamLocalitiesImport::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register commands
        $this->registerCommands($this->commands);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Bootstrap handle
        $this->bootMigrations();
    }

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach ($commands as $commandName => $command) {
            $method = "register{$commandName}Command";

            if (method_exists($this, $method)) {
                $this->{$method}();
            } else {
                $this->app->singleton($command);
            }
        }

        $this->commands(array_values($commands));
    }

    /**
     * Loading and publishing package's migrations
     *
     * @return void
     */
    protected function bootMigrations()
    {
        $packageMigrationsPath = __DIR__ . '/../database/migrations';

        $this->loadMigrationsFrom($packageMigrationsPath);

        $this->publishes([
            $packageMigrationsPath => database_path('migrations')
        ], 'migrations');
    }
}
