<?php

namespace App\Core\Session;

use App\Contract\EnvironmentServiceInterface;
use App\Contract\SessionManagerInterface;
use App\Contract\UserSessionRepositoryInterface;
use App\Contract\UserSessionServiceInterface;
use App\Core\Request;
use App\DTO\UserSessionDTO;

class UserSessionService implements UserSessionServiceInterface
{
    private ?UserSessionDTO $currentSession = null;

    public function __construct(
        private UserSessionRepositoryInterface $userSessionRepository,
        private SessionManagerInterface $sessionManager,
        private EnvironmentServiceInterface $environment
    ) {
    }

    public function getCurrentSession(?Request $request = null): UserSessionDTO
    {
        if ($this->currentSession !== null) {
            return $this->currentSession;
        }

        $sessionId = $this->sessionManager->getId();

        if ($sessionId === null) {
            $this->sessionManager->start();
            $sessionId = $this->sessionManager->getId();

            if ($sessionId === null) {
                throw new \RuntimeException('Unable to start session');
            }
        }

        $userSession = $this->userSessionRepository->findActiveBySessionId($sessionId);

        if ($userSession) {
            $this->userSessionRepository->updateLastActivity($userSession->id);
            $this->currentSession = $userSession;

            return $userSession;
        }

        $requestInfo = $this->getCurrentRequestInfo($request);

        $newUserSession = UserSessionDTO::create(
            userId: null,
            sessionId: $sessionId,
            ipAddress: $requestInfo['ip_address'],
            userAgent: $requestInfo['user_agent']
        );

        $this->currentSession = $this->userSessionRepository->create($newUserSession);

        return $this->currentSession;
    }

    public function bindUserToCurrentSession(int $userId, ?Request $request = null): UserSessionDTO
    {
        $currentSession = $this->getCurrentSession($request);

        $this->userSessionRepository->deactivate($currentSession->id);

        $this->sessionManager->regenerate();

        $sessionId = $this->sessionManager->getId();
        if ($sessionId === null) {
            throw new \RuntimeException('Unable to get session ID after regeneration');
        }

        $requestInfo = $this->getCurrentRequestInfo($request);

        $userSession = UserSessionDTO::create(
            userId: $userId,
            sessionId: $sessionId,
            ipAddress: $requestInfo['ip_address'],
            userAgent: $requestInfo['user_agent']
        );

        $this->currentSession = $this->userSessionRepository->create($userSession);

        return $this->currentSession;
    }

    public function unbindUserFromCurrentSession(?Request $request = null): UserSessionDTO
    {
        $currentSession = $this->getCurrentSession($request);

        if ($currentSession->userId !== null) {
            $this->userSessionRepository->deactivate($currentSession->id);

            $this->sessionManager->regenerate();

            $sessionId = $this->sessionManager->getId();
            if ($sessionId === null) {
                throw new \RuntimeException('Unable to get session ID after regeneration');
            }

            $requestInfo = $this->getCurrentRequestInfo($request);

            $anonymousSession = UserSessionDTO::create(
                userId: null,
                sessionId: $sessionId,
                ipAddress: $requestInfo['ip_address'],
                userAgent: $requestInfo['user_agent']
            );

            $this->currentSession = $this->userSessionRepository->create($anonymousSession);
        }

        if ($this->currentSession === null) {
            throw new \RuntimeException('Current session is null after unbinding user');
        }

        return $this->currentSession;
    }

    public function updateLastActivity(): bool
    {
        $currentSession = $this->getCurrentSession();

        return $this->userSessionRepository->updateLastActivity($currentSession->id);
    }

    public function getCurrentRequestInfo(?Request $request = null): array
    {
        return [
            'ip_address' => $this->environment->getClientIp($request),
            'user_agent' => $this->environment->getUserAgent($request),
        ];
    }
}
