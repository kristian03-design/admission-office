<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\CachePublicResponseHeaders;
use App\Http\Middleware\PreventPublicFormSpam;
use App\Http\Middleware\SecureHeaders;

$app = Application::configure(basePath: $_ENV['APP_BASE_PATH'] ?? $_SERVER['APP_BASE_PATH'] ?? dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        if (($_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? null) !== 'testing') {
            $middleware->trustProxies(at: '*');
        }
        $middleware->append(SecureHeaders::class);
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'public.cache' => CachePublicResponseHeaders::class,
            'public.spam' => PreventPublicFormSpam::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'backend/*',
            '*/api/*',
            '*/backend/*',
            'admin/settings',
            'admin/news-events',
            'admin/news-events/*',
            '*/admin/settings',
            '*/admin/news-events',
            '*/admin/news-events/*',
            'announcements',
            'announcements/*',
            '*/announcements',
            '*/announcements/*',
            'testimonials',
            'testimonials/*',
            '*/testimonials',
            '*/testimonials/*',
            'faculty-staff',
            'faculty-staff/*',
            '*/faculty-staff',
            '*/faculty-staff/*',
            'programs/*/schedule',
            'programs/*/slots-left',
            '*/programs/*/schedule',
            '*/programs/*/slots-left',
            'interviews/sync/*',
            '*/interviews/sync/*',
            'applications/submit-public',
            'applications/bulk-delete',
            'applications/*/documents',
            'applications/*/status',
            'applications/*/delete',
            '*/applications/submit-public',
            '*/applications/bulk-delete',
            '*/applications/*/documents',
            '*/applications/*/status',
            '*/applications/*/delete',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

if ($storagePath = $_ENV['LARAVEL_STORAGE_PATH'] ?? $_SERVER['LARAVEL_STORAGE_PATH'] ?? null) {
    $app->useStoragePath($storagePath);
}

if ($bootstrapPath = $_ENV['APP_BOOTSTRAP_PATH'] ?? $_SERVER['APP_BOOTSTRAP_PATH'] ?? null) {
    $app->useBootstrapPath($bootstrapPath);
}

return $app;
