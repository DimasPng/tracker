<?php

namespace App\Core;

final readonly class Request
{
    private function __construct(
        private array $get,
        private array $post,
        private array $server,
        private array $cookie,
        private array $files
    ) {
    }

    public static function capture(): self
    {
        return new self($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES);
    }

    public function getPath(): string
    {
        $path = $this->server['REQUEST_URI'] ?? '/';

        return parse_url($path, PHP_URL_PATH) ?: '/';
    }

    public function method(): string
    {
        if (!isset($this->server['REQUEST_METHOD'])) {
            throw new \RuntimeException('REQUEST_METHOD not set — this is not a valid HTTP request.');
        }

        return strtoupper($this->server['REQUEST_METHOD']);
    }

    public function get(string $key, ?string $default = null): ?string
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, ?string $default = null): ?string
    {
        return $this->post[$key] ?? $default;
    }

    public function cookie(string $key, ?string $default = null): ?string
    {
        return $this->cookie[$key] ?? $default;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function getAllServer(): array
    {
        return $this->server;
    }
}
