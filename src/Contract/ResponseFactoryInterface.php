<?php

namespace App\Contract;

use App\Core\Response;
use App\Enum\AppRoute;

interface ResponseFactoryInterface
{
    public function error(string $message, int $status = 400): Response;

    public function notFound(string $message = 'Not Found'): Response;

    public function html(string $content, int $status = 200): Response;

    public function redirect(string|AppRoute $url, int $status = 302): Response;

    public function download(string $filePath, ?string $filename = null): Response;
}
