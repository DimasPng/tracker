<?php

namespace App\Contract;

use App\DTO\UserSessionDTO;

interface UserSessionRepositoryInterface
{
    public function create(UserSessionDTO $userSession): UserSessionDTO;

    public function findActiveBySessionId(string $sessionId): ?UserSessionDTO;

    public function updateLastActivity(int $sessionId): bool;

    public function deactivate(int $sessionId): bool;
}
