<?php

namespace App\Middleware;

use App\Contract\MiddlewareInterface;
use App\Contract\ResponseFactoryInterface;
use App\Core\Request;
use App\Core\Response;

class HttpMethodMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private array $allowedMethods = []
    ) {
    }

    public function handle(Request $request): ?Response
    {
        if (empty($this->allowedMethods)) {
            return null;
        }

        $requestMethod = $request->method();

        if (!in_array($requestMethod, $this->allowedMethods, true)) {
            return $this->responseFactory->error(
                'Method Not Allowed. Expected: ' . implode(', ', $this->allowedMethods),
                405
            );
        }

        return null;
    }
}
