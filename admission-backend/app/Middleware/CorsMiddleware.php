<?php
// ============================================================
// app/Middleware/CorsMiddleware.php
// ============================================================

namespace App\Middleware;

class CorsMiddleware
{
    private array $config;

    public function __construct()
    {
        $appConfig    = require __DIR__ . '/../../config/app.php';
        $this->config = $appConfig['cors'];
    }

    public function handle(): void
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';

        // Check allowed origins
        $allowedOrigins = $this->config['allowed_origins'];
        if (in_array('*', $allowedOrigins, true)) {
            header('Access-Control-Allow-Origin: *');
        } elseif (in_array($origin, $allowedOrigins, true)) {
            header("Access-Control-Allow-Origin: $origin");
            header('Vary: Origin');
        }

        if ($this->config['allow_credentials']) {
            header('Access-Control-Allow-Credentials: true');
        }

        header('Access-Control-Allow-Methods: ' . implode(', ', $this->config['allowed_methods']));
        header('Access-Control-Allow-Headers: ' . implode(', ', $this->config['allowed_headers']));
        header('Access-Control-Max-Age: ' . $this->config['max_age']);

        if (!empty($this->config['expose_headers'])) {
            header('Access-Control-Expose-Headers: ' . implode(', ', $this->config['expose_headers']));
        }

        // Handle preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
