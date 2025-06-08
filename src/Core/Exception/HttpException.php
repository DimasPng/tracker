<?php

namespace App\Core\Exception;

use Exception;

class HttpException extends Exception
{
    private int $statusCode;

    public function __construct(int $statusCode, string $message = '', ?Exception $previous = null)
    {
        $this->statusCode = $statusCode;

        if (empty($message)) {
            $message = $this->getDefaultMessage($statusCode);
        }

        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    private function getDefaultMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
            default => 'HTTP Error'
        };
    }

    public static function notFound(string $message = ''): self
    {
        return new self(404, $message);
    }
}
