<?php

namespace App\Contract;

interface SessionStorageInterface
{
    public function start(): bool;

    public function isActive(): bool;

    public function setValue(string $key, mixed $value): void;

    public function getValue(string $key, mixed $default = null): mixed;

    public function hasKey(string $key): bool;

    public function getAll(): array;

    public function destroy(): bool;

    public function regenerateId(bool $deleteOldSession = true): bool;

    public function getId(): ?string;
}
