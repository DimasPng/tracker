<?php

namespace App\Provider;

use App\Contract\EnvironmentServiceInterface;
use PDO;

class SessionRecovery
{
    private const REMEMBER_TOKEN_LIFETIME = 30 * 24 * 3600;

    public function __construct(
        private readonly PDO $pdo,
        private readonly EnvironmentServiceInterface $environment
    ) {
    }

    public function createRememberToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + self::REMEMBER_TOKEN_LIFETIME);

        $stmt = $this->pdo->prepare('
            REPLACE INTO remember_tokens (user_id, token, expires_at, created_at) 
            VALUES (?, ?, ?, CURRENT_TIMESTAMP)
        ');

        $stmt->execute([$userId, hash('sha256', $token), $expiresAt]);

        setcookie('remember_token', $token, [
            'expires' => time() + self::REMEMBER_TOKEN_LIFETIME,
            'path' => '/',
            'httponly' => true,
            'secure' => $this->environment->isHttps(),
            'samesite' => 'Lax',
        ]);

        return $token;
    }

    public function clearRememberToken(?int $userId = null): void
    {
        if ($userId) {
            $stmt = $this->pdo->prepare('DELETE FROM remember_tokens WHERE user_id = ?');
            $stmt->execute([$userId]);
        }

        setcookie('remember_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => $this->environment->isHttps(),
            'samesite' => 'Lax',
        ]);
    }
}
