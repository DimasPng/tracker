<?php

namespace App\Controller;

use App\Contract\CurrentUserProviderInterface;
use App\Contract\ResponseFactoryInterface;
use App\Core\Response;
use App\Core\View;
use App\Enum\ActivityAction;
use App\Enum\AppRoute;
use App\Exception\AuthenticationException;
use App\Exception\RegistrationException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Service\ActivityLoggerService;
use App\Service\AuthService;
use App\Util\Logger;

class AuthController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly CurrentUserProviderInterface $currentUser,
        private readonly ActivityLoggerService $activityLogger,
        private readonly ResponseFactoryInterface   $responseFactory
    ) {
    }

    public function index(): Response
    {
        if ($this->currentUser->check()) {
            return $this->responseFactory->redirect(AppRoute::PAGE_A);
        }

        return $this->responseFactory->redirect(AppRoute::LOGIN);
    }

    public function login(LoginRequest $request): Response
    {
        try {
            if ($request->isPost()) {
                $validatedData = $request->validated();

                $this->authService->login($validatedData['email'], $validatedData['password']);

                $this->activityLogger->log(ActivityAction::LOGIN, AppRoute::LOGIN);

                return $this->responseFactory->redirect(AppRoute::PAGE_A);
            }

            $content = View::render('auth.login', ['hideNavbar' => true, 'hideFooter' => true]);

            return $this->responseFactory->html($content);
        } catch (AuthenticationException $e) {
            $content = View::render('auth.login', [
                'error' => $e->getMessage(),
                'email' => $request->validated()['email'] ?? '',
                'hideNavbar' => true,
                'hideFooter' => true,
            ]);

            return $this->responseFactory->html($content);
        } catch (\Throwable $e) {
            Logger::error('Login error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('Login service temporarily unavailable', 500);
        }
    }

    public function register(RegistrationRequest $request): Response
    {
        try {
            if ($request->isPost()) {
                $validatedData = $request->validated();

                $this->authService->register($validatedData['email'], $validatedData['password']);

                $this->activityLogger->log(ActivityAction::REGISTRATION, AppRoute::REGISTER);

                return $this->responseFactory->redirect(AppRoute::PAGE_A);
            }

            $content = View::render('auth.register', ['hideNavbar' => true, 'hideFooter' => true]);

            return $this->responseFactory->html($content);
        } catch (RegistrationException $e) {
            $content = View::render('auth.register', [
                'error' => $e->getMessage(),
                'email' => $request->validated()['email'] ?? '',
                'hideNavbar' => true,
                'hideFooter' => true,
            ]);

            return $this->responseFactory->html($content);
        } catch (\Throwable $e) {
            Logger::error('Registration error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->error('Registration service temporarily unavailable', 500);
        }
    }

    public function logout(): Response
    {
        try {
            $userId = null;

            if ($this->currentUser->check()) {
                $userId = $this->currentUser->id();
            }

            $this->activityLogger->log(ActivityAction::LOGOUT, AppRoute::LOGOUT);

            $this->authService->logout($userId);

            return $this->responseFactory->redirect(AppRoute::LOGIN);
        } catch (\Throwable $e) {
            Logger::error('Logout error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->responseFactory->redirect(AppRoute::LOGIN);
        }
    }
}
