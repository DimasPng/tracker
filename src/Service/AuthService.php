<?php

namespace App\Service;

use App\Contract\SessionManagerInterface;
use App\Contract\UserSessionServiceInterface;
use App\Enum\UserRole;
use App\Exception\AuthenticationException;
use App\Exception\RegistrationException;
use App\Provider\SessionRecovery;
use App\Repository\UserRepository;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SessionManagerInterface $session,
        private readonly UserSessionServiceInterface $userSessionService,
        private readonly SessionRecovery $sessionRecovery,
    ) {
    }

    public function login(string $email, string $password): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new AuthenticationException('Invalid email or password');
        }

        $this->authenticateUser($user);
    }

    public function register(string $email, string $password): void
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userRepository->create($email, $hashedPassword, UserRole::default());

        if (!$userId) {
            throw new RegistrationException('Failed to create user account');
        }

        $user = $this->userRepository->findById($userId);
        $this->authenticateUser($user);
    }

    public function logout(?int $userId = null): void
    {
        $this->sessionRecovery->clearRememberToken($userId);

        $this->userSessionService->unbindUserFromCurrentSession();

        $this->session->destroy();
        $this->session->start();

        app('csrf')->generateToken();
    }

    private function authenticateUser(array $user): void
    {
        $this->userSessionService->bindUserToCurrentSession($user['id']);

        app('csrf')->regenerateToken();

        $this->session->set('user_id', $user['id']);
        $this->session->set('user_email', $user['email']);
        $this->session->set('user_role', $user['role']);

        $this->sessionRecovery->createRememberToken($user['id']);
    }
}
