<?php

namespace App\ServiceProvider;

use App\Contract\CsrfManagerInterface;
use App\Contract\CurrentUserProviderInterface;
use App\Contract\EnvironmentServiceInterface;
use App\Contract\FormRequestFactoryInterface;
use App\Contract\ResponseFactoryInterface;
use App\Contract\SessionManagerInterface;
use App\Contract\UserSessionRepositoryInterface;
use App\Contract\UserSessionServiceInterface;
use App\Core\Environment\EnvironmentService;
use App\Core\Security\CsrfManager;
use App\Core\ServiceProvider\ServiceProvider;
use App\Core\Session\UserSessionService;
use App\Factory\FormRequestFactory;
use App\Provider\SessionCurrentUserProvider;
use App\Repository\UserSessionRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(EnvironmentServiceInterface::class, function () {
            return new EnvironmentService();
        });

        $this->container->singleton(CurrentUserProviderInterface::class, function ($container) {
            return new SessionCurrentUserProvider(
                $container->get(UserSessionServiceInterface::class),
                $container->get(SessionManagerInterface::class)
            );
        });

        $this->container->singleton(UserSessionRepositoryInterface::class, function ($container) {
            return new UserSessionRepository(
                $container->get('PDO')
            );
        });

        $this->container->singleton(UserSessionServiceInterface::class, function ($container) {
            return new UserSessionService(
                $container->get(UserSessionRepositoryInterface::class),
                $container->get(SessionManagerInterface::class),
                $container->get(EnvironmentServiceInterface::class)
            );
        });

        $this->container->bind(FormRequestFactoryInterface::class, function ($container) {
            return new FormRequestFactory(
                $container->get(ResponseFactoryInterface::class),
                $container
            );
        });

        $this->container->singleton(CsrfManagerInterface::class, function ($container) {
            return new CsrfManager(
                $container->get(SessionManagerInterface::class)
            );
        });

        $this->container->singleton('env', function ($container) {
            return $container->get(EnvironmentServiceInterface::class);
        });

        $this->container->singleton('auth', function ($container) {
            return $container->get(CurrentUserProviderInterface::class);
        });

        $this->container->singleton('response', function ($container) {
            return $container->get(ResponseFactoryInterface::class);
        });

        $this->container->singleton('csrf', function ($container) {
            return $container->get(CsrfManagerInterface::class);
        });

        $this->container->singleton('user.session', function ($container) {
            return $container->get(UserSessionServiceInterface::class);
        });
    }

    public function boot(): void
    {
    }
}
