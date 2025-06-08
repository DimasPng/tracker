<?php

namespace App\Core;

use App\Contract\ResponseFactoryInterface;
use App\Contract\RouterInterface;
use App\Core\DI\Container;
use App\Core\Exception\HttpException;
use App\Middleware\CsrfMiddleware;
use App\Middleware\HttpMethodMiddleware;

final class Router implements RouterInterface
{
    public function __construct(
        private readonly array $routes,
        private Request $request,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly Container $container
    ) {
    }

    public function dispatch(): void
    {
        $route = $this->resolveRoute($this->request->getPath());

        foreach ($this->getMiddlewarePipeline($route) as $middleware) {
            $response = $middleware->handle($this->request);
            if ($response !== null) {
                $response->send();

                return;
            }
        }

        $response = $this->invokeController($route['controller']);
        if ($response instanceof Response) {
            $response->send();
        }
    }

    private function resolveRoute(string $path): array
    {
        if (!isset($this->routes[$path])) {
            throw HttpException::notFound("Route '{$path}' not found.");
        }

        return $this->routes[$path];
    }

    private function getMiddlewarePipeline(array $route): array
    {
        $middlewares = [];

        if (!empty($route['methods'])) {
            $middlewares[] = new HttpMethodMiddleware($this->responseFactory, $route['methods']);
        }

        $middlewares[] = $this->container->get(CsrfMiddleware::class);

        foreach ($route['middleware'] ?? [] as $middlewareClass) {
            if (!class_exists($middlewareClass)) {
                throw new \RuntimeException("Middleware class '{$middlewareClass}' not found.");
            }
            $middlewares[] = $this->container->get($middlewareClass);
        }

        return $middlewares;
    }

    private function invokeController(array $controllerDefinition): ?Response
    {
        [$controllerClass, $action] = $controllerDefinition;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller class '{$controllerClass}' not found.");
        }

        $controller = $this->container->get($controllerClass);

        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Action '{$action}' not found in controller '{$controllerClass}'.");
        }

        return $this->container->call([$controller, $action], [$this->request]);
    }
}
