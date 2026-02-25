<?php
// ============================================================
// app/Controllers/AuthController.php
// Handles: register, login, logout, refresh, me
// ============================================================

namespace App\Controllers;

use App\Config\Database;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Services\JwtService;
use App\Services\EmailService;
use App\Middleware\AuthMiddleware;
use PDO;
use RuntimeException;

class AuthController
{
    private PDO        $db;
    private JwtService $jwt;

    public function __construct()
    {
        $this->db  = Database::pdo();
        $this->jwt = new JwtService();
    }

    // ── POST /api/auth/register ───────────────────────────────
    public function register(): void
    {
        $body = $this->jsonBody();

        $v = (new Validator($body))->validate([
            'name'                  => 'required|string|max:150',
            'email'                 => 'required|email|max:191',
            'password'              => 'required|string|min:8|max:72|confirmed',
            'role'                  => 'nullable|in:admin,staff,applicant',
        ]);

        if ($v->fails()) {
            Response::validationError($v->errors());
        }

        $clean = $v->sanitised();

        // Check email uniqueness
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$clean['email']]);
        if ($stmt->fetch()) {
            Response::error('Email already registered', 409);
        }

        $role = $clean['role'] ?? 'applicant';

        // Only admins can create staff accounts (enforce via separate admin endpoint)
        // For public registration we allow only applicant role
        if (in_array($role, ['admin', 'staff'], true)) {
            // Require existing admin token
            (new AuthMiddleware())->adminOnly();
        }

        $hash = password_hash($clean['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$clean['name'], $clean['email'], $hash, $role]);
        $userId = (int) $this->db->lastInsertId();

        // If applicant, create applicant row
        if ($role === 'applicant') {
            $appNo = 'APP-' . date('Y') . '-' . str_pad($userId, 6, '0', STR_PAD_LEFT);
            // Parse first/last name
            $nameParts = explode(' ', trim($clean['name']), 2);
            $stmt = $this->db->prepare(
                'INSERT INTO applicants (user_id, applicant_no, first_name, last_name,
                  gender, birthdate, address_line1, city)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $userId,
                $appNo,
                $nameParts[0],
                $nameParts[1] ?? '',
                'prefer_not_to_say',
                '2000-01-01',   // placeholder – applicant updates profile later
                'N/A',
                'N/A',
            ]);
        }

        // Send welcome email (non-blocking – swallow errors)
        try {
            (new EmailService())->sendWelcome($clean['email'], $clean['name']);
        } catch (\Throwable) {}

        $user = $this->fetchUser($userId);
        [$accessToken, $refreshToken] = $this->issueTokens($user);

        Response::success([
            'user'          => $this->publicUser($user),
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
        ], 'Registration successful', 201);
    }

    // ── POST /api/auth/login ──────────────────────────────────
    public function login(): void
    {
        $body = $this->jsonBody();

        $v = (new Validator($body))->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($v->fails()) {
            Response::validationError($v->errors());
        }

        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? AND is_active = 1');
        $stmt->execute([$body['email']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($body['password'], $user['password'])) {
            Response::error('Invalid email or password', 401);
        }

        [$accessToken, $refreshToken] = $this->issueTokens($user);

        Response::success([
            'user'          => $this->publicUser($user),
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
        ], 'Login successful');
    }

    // ── POST /api/auth/logout ─────────────────────────────────
    public function logout(): void
    {
        (new AuthMiddleware())->authenticated();

        $body  = $this->jsonBody(false);
        $token = $body['refresh_token'] ?? null;

        if ($token) {
            $this->jwt->revokeRefreshToken($token);
        }

        Response::success(null, 'Logged out successfully');
    }

    // ── POST /api/auth/refresh ────────────────────────────────
    public function refresh(): void
    {
        $body  = $this->jsonBody();
        $token = $body['refresh_token'] ?? null;

        if (!$token) {
            Response::error('Refresh token required', 400);
        }

        // Validate token signature & expiry
        try {
            $payload = $this->jwt->verify($token, 'refresh');
        } catch (RuntimeException $e) {
            Response::error('Invalid or expired refresh token', 401);
        }

        // Validate token exists in DB and is not revoked
        if (!$this->jwt->validateRefreshToken($token)) {
            Response::error('Refresh token has been revoked', 401);
        }

        // Rotate: revoke old, issue new
        $this->jwt->revokeRefreshToken($token);

        $user = $this->fetchUser((int) $payload['sub']);
        if (!$user || !$user['is_active']) {
            Response::error('User account is inactive', 401);
        }

        [$accessToken, $newRefreshToken] = $this->issueTokens($user);

        Response::success([
            'access_token'  => $accessToken,
            'refresh_token' => $newRefreshToken,
            'token_type'    => 'Bearer',
        ], 'Tokens refreshed');
    }

    // ── GET /api/auth/me ──────────────────────────────────────
    public function me(): void
    {
        (new AuthMiddleware())->authenticated();
        $userId = AuthMiddleware::authUserId();
        $user   = $this->fetchUser($userId);

        if (!$user) {
            Response::notFound('User not found');
        }

        Response::success($this->publicUser($user));
    }

    // ── POST /api/auth/forgot-password ───────────────────────
    public function forgotPassword(): void
    {
        $body = $this->jsonBody();
        $email = filter_var($body['email'] ?? '', FILTER_VALIDATE_EMAIL);

        if (!$email) {
            Response::error('Valid email required', 400);
        }

        // Always return success to prevent user enumeration
        $stmt = $this->db->prepare('SELECT id, name FROM users WHERE email = ? AND is_active = 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $token     = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);

            // Store reset token (use refresh_tokens table with type marker in payload is fine,
            // or a dedicated password_resets table – here we use a simple approach)
            $stmt = $this->db->prepare(
                'INSERT INTO refresh_tokens (user_id, token_hash, expires_at)
                 VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token_hash = VALUES(token_hash), expires_at = VALUES(expires_at)'
            );
            $stmt->execute([$user['id'], hash('sha256', $token), $expiresAt]);

            try {
                (new EmailService())->sendPasswordReset($email, $user['name'], $token);
            } catch (\Throwable) {}
        }

        Response::success(null, 'If that email exists, a reset link has been sent');
    }

    // ── Helpers ───────────────────────────────────────────────

    private function issueTokens(array $user): array
    {
        $claims = [
            'sub'  => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];

        $accessToken  = $this->jwt->generateAccessToken($claims);
        $refreshToken = $this->jwt->generateRefreshToken($claims);
        $this->jwt->storeRefreshToken((int) $user['id'], $refreshToken);

        return [$accessToken, $refreshToken];
    }

    private function fetchUser(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function publicUser(array $user): array
    {
        unset($user['password'], $user['remember_token']);
        return $user;
    }

    private function jsonBody(bool $required = true): array
    {
        $raw  = file_get_contents('php://input');
        $body = json_decode($raw, true) ?? [];

        if ($required && empty($body)) {
            // Also check $_POST as fallback
            $body = $_POST ?: [];
        }

        return $body;
    }
}
