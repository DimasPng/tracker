<?php

namespace App\Core\Session;

use App\Contract\SessionStorageInterface;

class PhpSessionStorage implements SessionStorageInterface
{
    public function start(): bool
    {
        if ($this->isActive()) {
            return true;
        }

        return session_start();
    }

    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function setValue(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function getValue(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function hasKey(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function getAll(): array
    {
        return $_SESSION;
    }

    public function destroy(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        return session_destroy();
    }

    public function regenerateId(bool $deleteOldSession = true): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        return session_regenerate_id($deleteOldSession);
    }

    public function getId(): ?string
    {
        return $this->isActive() ? session_id() : null;
    }
}
