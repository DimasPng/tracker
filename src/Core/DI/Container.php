<?php

namespace App\Core\DI;

use App\Contract\FormRequestFactoryInterface;
use App\Core\Exception\ValidationException;
use App\Core\FormRequest\AbstractFormRequest;
use App\Core\FormRequest\AbstractJsonRequest;
use App\Core\Request;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

class Container
{
    private array $bindings = [];

    private array $instances = [];

    private array $singletons = [];

    public function bind(string $abstract, $concrete = null, bool $singleton = false): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'singleton' => $singleton,
        ];

        if ($singleton) {
            $this->singletons[$abstract] = true;
        }
    }

    public function singleton(string $abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    public function instance(string $abstract, $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    public function get(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->singletons[$abstract]) && isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $instance = $this->make($abstract);

        if (isset($this->singletons[$abstract])) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    public function has(string $abstract): bool
    {
        return isset($this->instances[$abstract]) ||
               isset($this->bindings[$abstract]) ||
               class_exists($abstract);
    }

    public function make(string $abstract)
    {
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract]['concrete'];

            if (is_callable($concrete)) {
                return $concrete($this);
            }

            if (is_string($concrete)) {
                return $this->build($concrete);
            }

            return $concrete;
        }

        return $this->build($abstract);
    }

    private function build(string $concrete)
    {
        $reflection = new ReflectionClass($concrete);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $concrete();
        }

        $dependencies = $this->resolveDependencies($constructor->getParameters());

        return $reflection->newInstanceArgs($dependencies);
    }

    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $this->resolveDependency($parameter);
            $dependencies[] = $dependency;
        }

        return $dependencies;
    }

    private function resolveDependency(ReflectionParameter $parameter)
    {
        $type = $parameter->getType();

        if ($type === null) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            throw new Exception("Cannot resolve parameter {$parameter->getName()}");
        }

        if (!$type instanceof ReflectionNamedType) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            throw new Exception("Cannot resolve complex type for parameter {$parameter->getName()}");
        }

        $className = $type->getName();

        if ($type->isBuiltin()) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            throw new Exception("Cannot resolve primitive parameter {$parameter->getName()}");
        }

        return $this->get($className);
    }

    public function call($callback, array $parameters = [])
    {
        if (is_array($callback)) {
            [$class, $method] = $callback;
            $instance = is_string($class) ? $this->get($class) : $class;

            $methodParameters = $this->resolveMethodParameters($instance, $method, $parameters);

            return call_user_func_array([$instance, $method], $methodParameters);
        }

        return call_user_func_array($callback, $parameters);
    }

    private function resolveMethodParameters($instance, string $method, array $providedParameters): array
    {
        $reflection = $this->getReflectionMethod($instance, $method);
        $resolved = [];

        foreach ($reflection->getParameters() as $index => $param) {
            $resolved[] = $this->resolveParameter($param, $providedParameters, $index);
        }

        return $resolved;
    }

    private function getReflectionMethod($instance, string $method): ReflectionMethod
    {
        try {
            return new ReflectionMethod($instance, $method);
        } catch (ReflectionException $e) {
            $class = is_object($instance) ? get_class($instance) : $instance;
            throw new Exception("Method {$class}::{$method} not found: " . $e->getMessage());
        }
    }

    private function resolveParameter(ReflectionParameter $param, array $provided, int $index)
    {
        $type = $param->getType();
        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return $provided[$index] ?? null;
        }

        $className = $type->getName();

        if (is_subclass_of($className, AbstractFormRequest::class)) {
            return $this->resolveFormRequest($className, $provided);
        }

        if (is_subclass_of($className, AbstractJsonRequest::class)) {
            try {
                return $this->resolveJsonRequest($className, $provided);
            } catch (ValidationException $e) {
                $e->getResponse()->send();
                exit;
            }
        }

        return $provided[$index] ?? $this->get($className);
    }

    private function resolveFormRequest(string $formRequestClass, array $providedParameters): AbstractFormRequest
    {
        $request = null;
        foreach ($providedParameters as $param) {
            if ($param instanceof Request) {
                $request = $param;

                break;
            }
        }

        if (!$request) {
            throw new Exception('Request object is required to resolve FormRequest');
        }

        $factory = $this->get(FormRequestFactoryInterface::class);

        $formRequest = $factory->make($formRequestClass, $request);

        if ($request->isPost()) {
            if (!$formRequest->validate()) {
                $response = $formRequest->failedValidationResponse();
                $response->send();
                exit;
            }
        }

        return $formRequest;
    }

    private function resolveJsonRequest(string $jsonRequestClass, array $providedParameters): AbstractJsonRequest
    {
        $request = null;
        foreach ($providedParameters as $param) {
            if ($param instanceof Request) {
                $request = $param;

                break;
            }
        }

        if (!$request) {
            throw new Exception('Request object is required to resolve JsonRequest');
        }

        $responseFactory = $this->get('App\\Contract\\ResponseFactoryInterface');

        return new $jsonRequestClass($request, $responseFactory);
    }
}
