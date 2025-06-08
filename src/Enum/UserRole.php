<?php

namespace App\Enum;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';

    public static function all(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public static function default(): self
    {
        return self::USER;
    }
}
