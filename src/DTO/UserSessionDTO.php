<?php

namespace App\DTO;

class UserSessionDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $userId,
        public readonly string $sessionId,
        public readonly string $ipAddress,
        public readonly ?string $userAgent,
        public readonly string $startedAt,
        public readonly string $lastActivity,
        public readonly bool $isActive = true
    ) {
    }

    public static function create(
        ?int $userId,
        string $sessionId,
        string $ipAddress,
        ?string $userAgent
    ): self {
        return new self(
            id: 0,
            userId: $userId,
            sessionId: $sessionId,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
            startedAt: date('Y-m-d H:i:s'),
            lastActivity: date('Y-m-d H:i:s'),
            isActive: true
        );
    }
}
