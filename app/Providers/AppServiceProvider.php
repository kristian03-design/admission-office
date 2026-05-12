<?php

namespace App\Providers;

use App\Models\Program;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        \Illuminate\Pagination\Paginator::useTailwind();

        View::composer(['welcome', 'about', 'news-events', 'news-event-details', 'course-details'], function ($view) {
            $footerPrograms = Cache::remember('footer_programs', 300, function () {
                $fallbackNames = collect(config('academic_programs.fallback', []))
                    ->pluck('name');

                try {
                    $programs = Program::orderBy('name')->get(['id', 'name']);
                } catch (\Throwable $e) {
                    $programs = collect();
                }

                $existingNames = $programs
                    ->map(fn ($program) => mb_strtolower(trim((string) $program->name)))
                    ->all();

                $fallbackPrograms = $fallbackNames
                    ->reject(fn ($name) => in_array(mb_strtolower($name), $existingNames, true))
                    ->map(fn ($name) => (object) ['id' => null, 'name' => $name]);

                return $programs
                    ->concat($fallbackPrograms)
                    ->sortBy('name')
                    ->values();
            });

            $view->with('footerPrograms', $footerPrograms);
        });

        if ($this->app->environment('production') || env('VERCEL')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('api-login', function (Request $request) {
            return Limit::perMinute(5)->by(strtolower((string) $request->input('email')).'|'.$request->ip());
        });

        RateLimiter::for('public-form', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('document-upload', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        RateLimiter::for('admin-api', function (Request $request) {
            return Limit::perMinute(120)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
