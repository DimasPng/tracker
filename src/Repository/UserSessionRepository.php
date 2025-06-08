<?php

namespace App\Repository;

use App\Contract\UserSessionRepositoryInterface;
use App\DTO\UserSessionDTO;
use PDO;

class UserSessionRepository implements UserSessionRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function create(UserSessionDTO $userSession): UserSessionDTO
    {
        $sql = 'INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, started_at, last_activity, is_active) 
                VALUES (:user_id, :session_id, :ip_address, :user_agent, :started_at, :last_activity, :is_active)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userSession->userId,
            'session_id' => $userSession->sessionId,
            'ip_address' => $userSession->ipAddress,
            'user_agent' => $userSession->userAgent,
            'started_at' => $userSession->startedAt,
            'last_activity' => $userSession->lastActivity,
            'is_active' => $userSession->isActive ? 1 : 0,
        ]);

        $id = (int) $this->pdo->lastInsertId();

        return new UserSessionDTO(
            id: $id,
            userId: $userSession->userId,
            sessionId: $userSession->sessionId,
            ipAddress: $userSession->ipAddress,
            userAgent: $userSession->userAgent,
            startedAt: $userSession->startedAt,
            lastActivity: $userSession->lastActivity,
            isActive: $userSession->isActive
        );
    }

    public function findActiveBySessionId(string $sessionId): ?UserSessionDTO
    {
        $sql = 'SELECT * FROM user_sessions WHERE session_id = :session_id AND is_active = 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['session_id' => $sessionId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUserSession($row) : null;
    }

    public function updateLastActivity(int $sessionId): bool
    {
        $sql = 'UPDATE user_sessions SET last_activity = CURRENT_TIMESTAMP WHERE id = :id AND is_active = 1';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $sessionId]);
    }

    public function deactivate(int $sessionId): bool
    {
        $sql = 'UPDATE user_sessions SET is_active = 0 WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $sessionId]);
    }

    private function mapRowToUserSession(array $row): UserSessionDTO
    {
        return new UserSessionDTO(
            id: (int) $row['id'],
            userId: $row['user_id'] ? (int) $row['user_id'] : null,
            sessionId: $row['session_id'],
            ipAddress: $row['ip_address'],
            userAgent: $row['user_agent'],
            startedAt: $row['started_at'],
            lastActivity: $row['last_activity'],
            isActive: (bool) $row['is_active']
        );
    }
}
