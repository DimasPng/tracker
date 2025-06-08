<?php

namespace App\Provider;

use App\Contract\CurrentUserProviderInterface;
use App\Contract\SessionManagerInterface;
use App\Core\Session\UserSessionService;
use App\Enum\UserRole;

class SessionCurrentUserProvider implements CurrentUserProviderInterface
{
    public function __construct(
        private readonly UserSessionService $userSessionService,
        private readonly SessionManagerInterface $session
    ) {
    }

    public function check(): bool
    {
        $session = $this->userSessionService->getCurrentSession();

        return $session !== null && $session->userId !== null;
    }

    public function user(): ?array
    {
        if (!$this->check()) {
            return null;
        }

        return [
            'id' => $this->session->get('user_id'),
            'email' => $this->session->get('user_email'),
            'role' => $this->session->get('user_role'),
        ];
    }

    public function hasRole(UserRole $role): bool
    {
        if (!$this->check()) {
            return false;
        }

        $userRole = $this->session->get('user_role');

        return $userRole === $role->value;
    }

    public function id(): ?int
    {
        if (!$this->check()) {
            return null;
        }

        return $this->userSessionService->getCurrentSession()->userId;
    }
}
