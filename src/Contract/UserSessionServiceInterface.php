<?php

namespace App\Contract;

use App\Core\Request;
use App\DTO\UserSessionDTO;

interface UserSessionServiceInterface
{
    public function getCurrentSession(?Request $request = null): UserSessionDTO;

    public function bindUserToCurrentSession(int $userId, ?Request $request = null): UserSessionDTO;

    public function unbindUserFromCurrentSession(?Request $request = null): UserSessionDTO;

    public function updateLastActivity(): bool;

    public function getCurrentRequestInfo(?Request $request = null): array;
}
