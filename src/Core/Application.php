<?php

namespace App\Core;

use App\Contract\RouterInterface;
use App\Contract\ServiceProviderFactoryInterface;
use App\Core\DI\Container;
use App\Core\ServiceProvider\ServiceProvider;
use App\Factory\ServiceProviderFactory;

class Application extends Container
{
    private array $providers = [];

    private array $bootedProviders = [];

    private bool $booted = false;

    private ?ServiceProviderFactoryInterface $providerFactory = null;

    private static ?Application $instance = null;

    public function __construct()
    {
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
        $this->instance(Application::class, $this);

        self::$instance = $this;

        $this->registerCoreServices();
    }

    public static function getInstance(): ?Application
    {
        return self::$instance;
    }

    private function registerCoreServices(): void
    {
        $this->singleton(ServiceProviderFactoryInterface::class, function ($container) {
            return $container->make(ServiceProviderFactory::class);
        });
    }

    public function register(string $providerClass): ServiceProvider
    {
        if (isset($this->providers[$providerClass])) {
            return $this->providers[$providerClass];
        }

        $factory = $this->getProviderFactory();
        $provider = $factory->create($providerClass, $this);

        $provider->register();

        $this->providers[$providerClass] = $provider;

        if ($this->booted) {
            $this->bootProvider($provider);
        }

        return $provider;
    }

    private function getProviderFactory(): ServiceProviderFactoryInterface
    {
        if ($this->providerFactory === null) {
            $this->providerFactory = $this->get(ServiceProviderFactoryInterface::class);
        }

        return $this->providerFactory;
    }

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        foreach ($this->providers as $provider) {
            $this->bootProvider($provider);
        }

        $this->booted = true;
    }

    private function bootProvider(ServiceProvider $provider): void
    {
        $providerClass = get_class($provider);

        if (isset($this->bootedProviders[$providerClass])) {
            return;
        }

        $provider->boot();
        $this->bootedProviders[$providerClass] = true;
    }

    public function run(): void
    {
        $this->boot();

        $router = $this->get(RouterInterface::class);
        $router->dispatch();
    }
}
