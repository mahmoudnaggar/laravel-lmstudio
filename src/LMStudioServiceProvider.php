<?php

namespace MahmoudNaggar\LaravelLMStudio;

use Illuminate\Support\ServiceProvider;
use MahmoudNaggar\LaravelLMStudio\Console\Commands\ChatCommand;
use MahmoudNaggar\LaravelLMStudio\Console\Commands\ListModelsCommand;
use MahmoudNaggar\LaravelLMStudio\Console\Commands\LoadModelCommand;
use MahmoudNaggar\LaravelLMStudio\Console\Commands\TestConnectionCommand;

class LMStudioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/lmstudio.php',
            'lmstudio'
        );

        $this->app->singleton(LMStudioClient::class, function ($app) {
            return new LMStudioClient(config('lmstudio'));
        });

        $this->app->alias(LMStudioClient::class, 'lmstudio');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish configuration
            $this->publishes([
                __DIR__ . '/../config/lmstudio.php' => config_path('lmstudio.php'),
            ], 'lmstudio-config');

            // Register commands
            $this->commands([
                ChatCommand::class,
                ListModelsCommand::class,
                LoadModelCommand::class,
                TestConnectionCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            LMStudioClient::class,
            'lmstudio',
        ];
    }
}
