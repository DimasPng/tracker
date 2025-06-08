<?php

namespace App\ServiceProvider;

use App\Contract\ResponseFactoryInterface;
use App\Contract\RouterInterface;
use App\Core\Request;
use App\Core\Router;
use App\Core\ServiceProvider\ServiceProvider;
use App\Factory\ResponseFactory;

class RouterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Request::class, function () {
            return Request::capture();
        });

        $this->container->singleton(ResponseFactoryInterface::class, function () {
            return new ResponseFactory();
        });

        $this->container->singleton(ResponseFactory::class, function ($container) {
            return $container->get(ResponseFactoryInterface::class);
        });

        $this->container->singleton('routes', function () {
            return require __DIR__ . '/../../routes/web.php';
        });

        $this->container->singleton(RouterInterface::class, function ($container) {
            return new Router(
                $container->get('routes'),
                $container->get(Request::class),
                $container->get(ResponseFactoryInterface::class),
                $container
            );
        });

        $this->container->singleton(Router::class, function ($container) {
            return $container->get(RouterInterface::class);
        });
    }
}
