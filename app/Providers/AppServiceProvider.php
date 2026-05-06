<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // On Vercel, the filesystem is read-only except /tmp.
        // Redirect all writable Laravel paths to /tmp.
        if (isset($_ENV['APP_STORAGE_PATH'])) {
            /** @var \Illuminate\Foundation\Application $app */
            $app = $this->app;
            $app->useStoragePath($_ENV['APP_STORAGE_PATH']);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || env('VERCEL')) {
            URL::forceScheme('https');
        }
    }
}
