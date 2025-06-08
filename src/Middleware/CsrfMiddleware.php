<?php

namespace App\Middleware;

use App\Contract\CsrfManagerInterface;
use App\Contract\EnvironmentServiceInterface;
use App\Contract\MiddlewareInterface;
use App\Contract\ResponseFactoryInterface;
use App\Core\Request;
use App\Core\Response;

class CsrfMiddleware implements MiddlewareInterface
{
    private const array SAFE_METHODS = ['GET', 'HEAD', 'OPTIONS'];

    public function __construct(
        private readonly CsrfManagerInterface $csrfManager,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly EnvironmentServiceInterface $environment
    ) {
    }

    public function handle(Request $request): ?Response
    {
        if (in_array($request->method(), self::SAFE_METHODS)) {
            return null;
        }

        $token = $this->getTokenFromRequest($request);

        if (!$this->csrfManager->validateToken($token)) {
            return $this->responseFactory->error('CSRF token mismatch', 419);
        }

        return null;
    }

    private function getTokenFromRequest(Request $request): string
    {
        $token = $request->post($this->csrfManager->getTokenName());

        if (!empty($token)) {
            return $token;
        }

        return $this->environment->getServerVar('HTTP_X_CSRF_TOKEN', '');
    }
}
