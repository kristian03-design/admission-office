<?php
// ============================================================
// config/app.php  –  Core configuration
// ============================================================

// Load .env file
(function () {
    $envFile = dirname(__DIR__) . '/.env';
    if (!file_exists($envFile)) {
        throw new RuntimeException('.env file not found. Copy .env.example to .env');
    }
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        // Strip surrounding quotes
        if (preg_match('/^"(.*)"$/', $value, $m) || preg_match("/^'(.*)'$/", $value, $m)) {
            $value = $m[1];
        }
        $_ENV[$key]    = $value;
        $_SERVER[$key] = $value;
        putenv("$key=$value");
    }
})();

function env(string $key, mixed $default = null): mixed
{
    $val = $_ENV[$key] ?? getenv($key);
    if ($val === false) return $default;
    return match (strtolower($val)) {
        'true', '(true)'   => true,
        'false', '(false)' => false,
        'null', '(null)'   => null,
        default            => $val,
    };
}

return [
    'name'    => env('APP_NAME', 'Admission API'),
    'env'     => env('APP_ENV', 'production'),
    'debug'   => env('APP_DEBUG', false),
    'url'     => env('APP_URL', 'http://localhost'),

    'db' => [
        'host'     => env('DB_HOST', '127.0.0.1'),
        'port'     => (int) env('DB_PORT', 3306),
        'database' => env('DB_DATABASE', 'admission_db'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset'  => 'utf8mb4',
    ],

    'jwt' => [
        'secret'         => env('JWT_SECRET'),
        'access_expiry'  => (int) env('JWT_ACCESS_EXPIRY', 900),
        'refresh_expiry' => (int) env('JWT_REFRESH_EXPIRY', 604800),
        'algorithm'      => env('JWT_ALGORITHM', 'HS256'),
    ],

    'cors' => [
        'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept'],
        'expose_headers'  => [],
        'max_age'         => 3600,
        'allow_credentials' => true,
    ],

    'upload' => [
        'max_size'      => (int) env('UPLOAD_MAX_SIZE', 5242880),
        'allowed_types' => explode(',', env('UPLOAD_ALLOWED_TYPES', 'pdf,jpg,jpeg,png')),
        'path'          => dirname(__DIR__) . '/' . env('UPLOAD_PATH', 'storage/uploads'),
    ],

    'mail' => [
        'driver'     => env('MAIL_DRIVER', 'smtp'),
        'host'       => env('MAIL_HOST', 'localhost'),
        'port'       => (int) env('MAIL_PORT', 587),
        'username'   => env('MAIL_USERNAME'),
        'password'   => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@admission.edu'),
            'name'    => env('MAIL_FROM_NAME', 'Admission Office'),
        ],
    ],

    'log' => [
        'level' => env('LOG_LEVEL', 'error'),
        'path'  => dirname(__DIR__) . '/' . env('LOG_PATH', 'storage/logs/app.log'),
    ],
];
