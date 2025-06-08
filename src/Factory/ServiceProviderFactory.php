<?php

namespace App\Factory;

use App\Contract\ServiceProviderFactoryInterface;
use App\Core\DI\Container;
use App\Core\ServiceProvider\ServiceProvider;

class ServiceProviderFactory implements ServiceProviderFactoryInterface
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(string $providerClass, Container $container): ServiceProvider
    {
        return $this->container->make($providerClass);
    }
}
