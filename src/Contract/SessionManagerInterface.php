<?php

namespace App\Contract;

interface SessionManagerInterface
{
    public function start(): void;

    public function set(string $key, mixed $value): void;

    public function get(string $key, mixed $default = null): mixed;

    public function has(string $key): bool;

    public function destroy(): void;

    public function regenerate(bool $deleteOldSession = true): void;

    public function getId(): ?string;

    public function isActive(): bool;

    public function all(): array;
}
