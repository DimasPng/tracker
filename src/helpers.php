<?php

use App\Contract\CurrentUserProviderInterface;
use App\Contract\EnvironmentServiceInterface;
use App\Core\Application;

function app(?string $abstract = null)
{
    $container = Application::getInstance();

    if ($container === null) {
        throw new RuntimeException('Application not initialized');
    }

    if ($abstract === null) {
        return $container;
    }

    return $container->get($abstract);
}

function auth(): CurrentUserProviderInterface
{
    return app(CurrentUserProviderInterface::class);
}

function getCurrentUri(): string
{
    $env = app(EnvironmentServiceInterface::class);

    return $env->get('REQUEST_URI', '');
}
