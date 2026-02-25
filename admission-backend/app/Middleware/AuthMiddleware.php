<?php
// ============================================================
// app/Middleware/AuthMiddleware.php
// Validates JWT on protected routes & enforces RBAC
// ============================================================

namespace App\Middleware;

use App\Services\JwtService;
use App\Helpers\Response;
use RuntimeException;

class AuthMiddleware
{
    private JwtService $jwt;

    public function __construct()
    {
        $this->jwt = new JwtService();
    }

    /**
     * Require a valid access token.
     * Sets $_REQUEST['auth_user'] with the JWT payload.
     *
     * @param array $allowedRoles  e.g. ['admin','staff']  – empty = any role
     */
    public function handle(array $allowedRoles = []): void
    {
        $token = JwtService::extractBearerToken();

        if (!$token) {
            Response::json(null, 'Authentication required', 401);
            exit;
        }

        try {
            $payload = $this->jwt->verify($token, 'access');
        } catch (RuntimeException $e) {
            Response::json(null, $e->getMessage(), 401);
            exit;
        }

        // Role-based access control
        if (!empty($allowedRoles) && !in_array($payload['role'] ?? '', $allowedRoles, true)) {
            Response::json(null, 'Insufficient permissions', 403);
            exit;
        }

        // Make payload available to controllers
        $_REQUEST['auth_user'] = $payload;
    }

    /** Shortcut: admin only */
    public function adminOnly(): void
    {
        $this->handle(['admin']);
    }

    /** Shortcut: admin or staff */
    public function staffOrAdmin(): void
    {
        $this->handle(['admin', 'staff']);
    }

    /** Shortcut: any authenticated user */
    public function authenticated(): void
    {
        $this->handle([]);
    }

    /** Get the currently authenticated user payload */
    public static function authUser(): ?array
    {
        return $_REQUEST['auth_user'] ?? null;
    }

    public static function authUserId(): ?int
    {
        return isset($_REQUEST['auth_user']['sub'])
            ? (int) $_REQUEST['auth_user']['sub']
            : null;
    }

    public static function authRole(): ?string
    {
        return $_REQUEST['auth_user']['role'] ?? null;
    }
}
