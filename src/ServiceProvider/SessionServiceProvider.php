<?php

namespace App\ServiceProvider;

use App\Contract\SessionManagerInterface;
use App\Contract\SessionStorageInterface;
use App\Core\ServiceProvider\ServiceProvider;
use App\Core\Session\PhpSessionStorage;
use App\Core\Session\SessionManager;

class SessionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(SessionStorageInterface::class, function () {
            return new PhpSessionStorage();
        });

        $this->container->singleton(SessionManagerInterface::class, function ($container) {
            return new SessionManager(
                $container->get(SessionStorageInterface::class)
            );
        });

        $this->container->singleton('session', function ($container) {
            return $container->get(SessionManagerInterface::class);
        });
    }

    public function boot(): void
    {
        $sessionManager = $this->container->get(SessionManagerInterface::class);
        $sessionManager->start();
    }
}
