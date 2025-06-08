<?php

namespace App\Core;

final class Response
{
    private int $statusCode;

    private array $headers = [];

    private string $body = '';

    public function __construct(string $content = '', int $statusCode = 200)
    {
        $this->body = $content;
        $this->statusCode = $statusCode;
    }

    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->body;
    }
}
