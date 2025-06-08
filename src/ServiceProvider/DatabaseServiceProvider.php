<?php

namespace App\ServiceProvider;

use App\Contract\EnvironmentServiceInterface;
use App\Core\Database\Connection;
use App\Core\ServiceProvider\ServiceProvider;
use App\Repository\UserRepository;
use PDO;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(PDO::class, function ($container) {
            $environment = $container->get(EnvironmentServiceInterface::class);
            Connection::setEnvironmentService($environment);

            return Connection::getInstance();
        });

        $this->container->singleton('db', function ($container) {
            return $container->get(PDO::class);
        });

        $this->container->singleton(UserRepository::class, function ($container) {
            return new UserRepository($container->get(PDO::class));
        });
    }

    public function boot(): void
    {
        $this->container->get(PDO::class);
    }
}
