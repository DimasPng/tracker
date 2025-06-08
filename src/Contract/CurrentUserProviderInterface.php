<?php

namespace App\Contract;

use App\Enum\UserRole;

interface CurrentUserProviderInterface
{
    public function check(): bool;

    public function user(): ?array;

    public function hasRole(UserRole $role): bool;

    public function id(): ?int;
}
