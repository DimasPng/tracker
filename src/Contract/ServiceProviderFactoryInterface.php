<?php

namespace App\Contract;

use App\Core\DI\Container;
use App\Core\ServiceProvider\ServiceProvider;

interface ServiceProviderFactoryInterface
{
    public function create(string $providerClass, Container $container): ServiceProvider;
}
