<?php

namespace App\Factory;

use App\Contract\ResponseFactoryInterface;
use App\Core\Response;
use App\Enum\AppRoute;

class ResponseFactory implements ResponseFactoryInterface
{
    public function error(string $message, int $status = 400): Response
    {
        return new Response($message, $status);
    }

    public function notFound(string $message = 'Not Found'): Response
    {
        return new Response("<h1>404</h1><p>{$message}</p>", 404);
    }

    public function html(string $content, int $status = 200): Response
    {
        $response = new Response($content, $status);
        $response->addHeader('Content-Type', 'text/html; charset=UTF-8');

        return $response;
    }

    public function redirect(string|AppRoute $url, int $status = 302): Response
    {
        if ($url instanceof AppRoute) {
            $url = $url->value;
        }

        $response = new Response('', $status);
        $response->addHeader('Location', $url);

        return $response;
    }

    public function download(string $filePath, ?string $filename = null): Response
    {
        if (!file_exists($filePath)) {
            return $this->notFound('File not found');
        }

        $filename = $filename ?: basename($filePath);
        $fileSize = filesize($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        $content = file_get_contents($filePath);
        $response = new Response($content, 200);

        $response->addHeader('Content-Type', $mimeType);
        $response->addHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->addHeader('Content-Length', (string)$fileSize);
        $response->addHeader('Cache-Control', 'no-cache, must-revalidate');
        $response->addHeader('Expires', '0');

        return $response;
    }
}
