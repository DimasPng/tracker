<?php

namespace App\Exception;

class RegistrationException extends \Exception
{
    public function __construct(string $message = 'Registration failed', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
