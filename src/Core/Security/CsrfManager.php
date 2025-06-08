<?php

namespace App\Core\Security;

use App\Contract\CsrfManagerInterface;
use App\Contract\SessionManagerInterface;

class CsrfManager implements CsrfManagerInterface
{
    private const string TOKEN_NAME = '_csrf_token';
    private const int TOKEN_LENGTH = 32;

    public function __construct(
        private readonly SessionManagerInterface $session
    ) {
    }

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $this->session->set(self::TOKEN_NAME, $token);

        return $token;
    }

    public function validateToken(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $sessionToken = $this->session->get(self::TOKEN_NAME);
        if (empty($sessionToken)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public function getToken(): string
    {
        $token = $this->session->get(self::TOKEN_NAME);

        if (empty($token)) {
            return $this->generateToken();
        }

        return $token;
    }

    public function getTokenName(): string
    {
        return self::TOKEN_NAME;
    }

    public function regenerateToken(): string
    {
        return $this->generateToken();
    }
}
