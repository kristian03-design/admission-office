<?php
// ============================================================
// app/Services/JwtService.php  –  Pure-PHP JWT (HS256)
// No third-party library required
// ============================================================

namespace App\Services;

use App\Config\Database;
use RuntimeException;

class JwtService
{
    private string $secret;
    private int    $accessExpiry;
    private int    $refreshExpiry;
    private string $algorithm = 'HS256';

    public function __construct()
    {
        $config              = require __DIR__ . '/../../config/app.php';
        $this->secret        = $config['jwt']['secret']
            ?? throw new RuntimeException('JWT_SECRET not configured');
        $this->accessExpiry  = $config['jwt']['access_expiry'];
        $this->refreshExpiry = $config['jwt']['refresh_expiry'];
    }

    // ── Token generation ─────────────────────────────────────

    public function generateAccessToken(array $payload): string
    {
        $payload['type'] = 'access';
        return $this->encode($payload, $this->accessExpiry);
    }

    public function generateRefreshToken(array $payload): string
    {
        $payload['type'] = 'refresh';
        return $this->encode($payload, $this->refreshExpiry);
    }

    private function encode(array $payload, int $expiry): string
    {
        $header = $this->base64UrlEncode(json_encode([
            'alg' => $this->algorithm,
            'typ' => 'JWT',
        ]));

        $now             = time();
        $payload['iat']  = $now;
        $payload['exp']  = $now + $expiry;
        $payload['jti']  = bin2hex(random_bytes(16));  // unique token id

        $claims    = $this->base64UrlEncode(json_encode($payload));
        $signature = $this->sign("$header.$claims");

        return "$header.$claims.$signature";
    }

    // ── Token verification ───────────────────────────────────

    /**
     * @throws RuntimeException on invalid / expired token
     */
    public function verify(string $token, string $expectedType = 'access'): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new RuntimeException('Invalid token format');
        }

        [$header, $claims, $signature] = $parts;

        // Verify signature
        $expectedSig = $this->sign("$header.$claims");
        if (!hash_equals($expectedSig, $signature)) {
            throw new RuntimeException('Invalid token signature');
        }

        $payload = json_decode($this->base64UrlDecode($claims), true);

        if (!$payload) {
            throw new RuntimeException('Invalid token payload');
        }

        // Check expiry
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new RuntimeException('Token expired');
        }

        // Check type
        if (($payload['type'] ?? '') !== $expectedType) {
            throw new RuntimeException("Invalid token type: expected $expectedType");
        }

        return $payload;
    }

    // ── Refresh token DB management ──────────────────────────

    public function storeRefreshToken(int $userId, string $token): void
    {
        $pdo  = Database::pdo();
        $hash = $this->hashToken($token);
        $exp  = date('Y-m-d H:i:s', time() + $this->refreshExpiry);

        $stmt = $pdo->prepare(
            'INSERT INTO refresh_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)'
        );
        $stmt->execute([$userId, $hash, $exp]);
    }

    public function validateRefreshToken(string $token): bool
    {
        $pdo  = Database::pdo();
        $hash = $this->hashToken($token);

        $stmt = $pdo->prepare(
            'SELECT id FROM refresh_tokens
             WHERE token_hash = ? AND revoked = 0 AND expires_at > NOW()'
        );
        $stmt->execute([$hash]);
        return $stmt->fetchColumn() !== false;
    }

    public function revokeRefreshToken(string $token): void
    {
        $pdo  = Database::pdo();
        $hash = $this->hashToken($token);

        $stmt = $pdo->prepare('UPDATE refresh_tokens SET revoked = 1 WHERE token_hash = ?');
        $stmt->execute([$hash]);
    }

    public function revokeAllUserTokens(int $userId): void
    {
        $pdo  = Database::pdo();
        $stmt = $pdo->prepare('UPDATE refresh_tokens SET revoked = 1 WHERE user_id = ?');
        $stmt->execute([$userId]);
    }

    // ── Helpers ──────────────────────────────────────────────

    private function sign(string $data): string
    {
        return $this->base64UrlEncode(
            hash_hmac('sha256', $data, $this->secret, true)
        );
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', (4 - strlen($data) % 4) % 4));
    }

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    /** Extract Bearer token from Authorization header */
    public static function extractBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION']
            ?? getallheaders()['Authorization']
            ?? null;

        if ($header && preg_match('/^Bearer\s+(.+)$/i', $header, $m)) {
            return $m[1];
        }
        return null;
    }
}
