<?php

namespace App\Contract;

interface CsrfManagerInterface
{
    public function generateToken(): string;

    public function validateToken(string $token): bool;

    public function getToken(): string;

    public function getTokenName(): string;

    public function regenerateToken(): string;
}
