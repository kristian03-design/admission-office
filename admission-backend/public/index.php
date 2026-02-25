<?php
// ============================================================
// public/index.php  –  Application entry point
// All requests are routed here via .htaccess / nginx
// ============================================================

declare(strict_types=1);

// ── Autoloader (PSR-4 style, no Composer required) ───────────

spl_autoload_register(function (string $class): void {
    // Map namespace prefix to directory
    $prefixMap = [
        'App\\Controllers\\' => __DIR__ . '/../app/Controllers/',
        'App\\Middleware\\'  => __DIR__ . '/../app/Middleware/',
        'App\\Services\\'   => __DIR__ . '/../app/Services/',
        'App\\Helpers\\'    => __DIR__ . '/../app/Helpers/',
        'App\\Models\\'     => __DIR__ . '/../app/Models/',
        'App\\Config\\'     => __DIR__ . '/../config/',
    ];

    foreach ($prefixMap as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file     = $dir . str_replace('\\', '/', $relative) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }

    // Fallback: try vendor autoload (PHPMailer etc.)
    $vendorAutoload = __DIR__ . '/../vendor/autoload.php';
    if (file_exists($vendorAutoload)) {
        require_once $vendorAutoload;
    }
});

// ── Bootstrap ────────────────────────────────────────────────

// Load config (also parses .env)
$appConfig = require __DIR__ . '/../config/app.php';

// Error reporting
if ($appConfig['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Global error handler – return JSON instead of HTML
set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    if ($errno & error_reporting()) {
        error_log("[$errno] $errstr in $errfile:$errline");
    }
    return true;
});

set_exception_handler(function (\Throwable $e): void {
    error_log('Uncaught exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    header('Content-Type: application/json');
    $debug = $_ENV['APP_DEBUG'] ?? 'false';
    echo json_encode([
        'success' => false,
        'message' => $debug === 'true' ? $e->getMessage() : 'Internal server error',
        'data'    => null,
    ]);
    exit;
});

// Set JSON content type globally for API
header('Content-Type: application/json; charset=utf-8');

// Configure logging
$logPath = $appConfig['log']['path'];
$logDir  = dirname($logPath);
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
ini_set('error_log', $logPath);

// ── Route ─────────────────────────────────────────────────────

require __DIR__ . '/../routes/api.php';
