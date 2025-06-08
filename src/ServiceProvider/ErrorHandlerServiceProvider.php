<?php

namespace App\ServiceProvider;

use App\Contract\EnvironmentServiceInterface;
use App\Core\DI\Container;
use App\Core\Error\ErrorHandler;
use App\Core\ServiceProvider\ServiceProvider;

class ErrorHandlerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(ErrorHandler::class, function (Container $container) {
            $environment = $container->get(EnvironmentServiceInterface::class);
            $debug = ($environment->get('APP_DEBUG', 'false')) === 'true';

            return new ErrorHandler($debug, $container);
        });
    }

    public function boot(): void
    {
        $errorHandler = $this->container->get(ErrorHandler::class);
        $errorHandler->register();
    }
}
