<?php

namespace App\Middleware;

use App\Contract\CurrentUserProviderInterface;
use App\Contract\MiddlewareInterface;
use App\Contract\ResponseFactoryInterface;
use App\Core\Request;
use App\Core\Response;
use App\Enum\AppRoute;
use App\Enum\UserRole;

class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly CurrentUserProviderInterface $currentUser,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function handle(Request $request): ?Response
    {
        if (!$this->currentUser->check()) {
            return $this->responseFactory->redirect(AppRoute::LOGIN);
        }

        if (!$this->currentUser->hasRole(UserRole::ADMIN)) {
            return $this->responseFactory->error('Access denied', 403);
        }

        return null;
    }
}
