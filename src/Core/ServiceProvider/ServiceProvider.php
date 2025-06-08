<?php

namespace App\Core\ServiceProvider;

use App\Core\DI\Container;

abstract class ServiceProvider
{
    public function __construct(
        protected Container $container
    ) {
    }

    abstract public function register(): void;

    public function boot(): void
    {
    }
}
