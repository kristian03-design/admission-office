<?php
// ============================================================
// app/Helpers/Response.php  –  Standard API response wrapper
// ============================================================

namespace App\Helpers;

class Response
{
    /**
     * Emit a JSON response and terminate execution.
     *
     * @param mixed  $data     Response payload (null if error)
     * @param string $message  Human-readable message
     * @param int    $status   HTTP status code
     */
    public static function json(mixed $data, string $message = '', int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'data'    => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function success(mixed $data, string $message = 'Success', int $status = 200): void
    {
        self::json($data, $message, $status);
    }

    public static function error(string $message, int $status = 400, mixed $data = null): void
    {
        self::json($data, $message, $status);
    }

    public static function validationError(array $errors): void
    {
        self::json(['errors' => $errors], 'Validation failed', 422);
    }

    public static function notFound(string $message = 'Resource not found'): void
    {
        self::json(null, $message, 404);
    }

    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::json(null, $message, 401);
    }

    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::json(null, $message, 403);
    }

    public static function serverError(string $message = 'Internal server error'): void
    {
        self::json(null, $message, 500);
    }
}
