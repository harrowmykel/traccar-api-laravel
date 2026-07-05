<?php
namespace PiccmaQ\TraccarApiLaravel;

use Illuminate\Support\ServiceProvider;

class TraccarProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/traccar.php', 'traccar');
        $this->app->singleton(TraccarLaravelClient::class, static function (): TraccarLaravelClient {
            return new TraccarLaravelClient(config('traccar.default_server'));
        });
        $this->app->alias(TraccarLaravelClient::class, 'traccar_laravel');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/traccar.php' => config_path('traccar.php'),
            ], 'config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            TraccarLaravelClient::class,
            'traccar_laravel',
        ];
    }
}