<?php

namespace App\Core\Session;

use App\Contract\SessionManagerInterface;
use App\Contract\SessionStorageInterface;

class SessionManager implements SessionManagerInterface
{
    private bool $started = false;

    public function __construct(
        private readonly SessionStorageInterface $storage
    ) {
    }

    public function start(): void
    {
        if ($this->started || $this->storage->isActive()) {
            return;
        }

        if ($this->storage->start()) {
            $this->started = true;
        } else {
            throw new \RuntimeException('Failed to start session');
        }
    }

    public function set(string $key, mixed $value): void
    {
        $this->ensureStarted();
        $this->storage->setValue($key, $value);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureStarted();

        return $this->storage->getValue($key, $default);
    }

    public function has(string $key): bool
    {
        $this->ensureStarted();

        return $this->storage->hasKey($key);
    }

    public function destroy(): void
    {
        if ($this->isActive()) {
            $this->storage->destroy();
            $this->started = false;
        }
    }

    public function regenerate(bool $deleteOldSession = true): void
    {
        $this->ensureStarted();
        $this->storage->regenerateId($deleteOldSession);
    }

    public function getId(): ?string
    {
        return $this->storage->getId();
    }

    public function isActive(): bool
    {
        return $this->storage->isActive();
    }

    public function all(): array
    {
        $this->ensureStarted();

        return $this->storage->getAll();
    }

    private function ensureStarted(): void
    {
        if (!$this->started && !$this->isActive()) {
            $this->start();
        }
    }
}
