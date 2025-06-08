<?php

namespace App\Factory;

use App\Contract\FormRequestFactoryInterface;
use App\Contract\ResponseFactoryInterface;
use App\Core\DI\Container;
use App\Core\FormRequest\AbstractFormRequest;
use App\Core\Request;

class FormRequestFactory implements FormRequestFactoryInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly Container $container
    ) {
    }

    public function make(string $formRequestClass, Request $request): AbstractFormRequest
    {
        try {
            return $this->container->make($formRequestClass);
        } catch (\Exception $e) {
            return new $formRequestClass($request, $this->responseFactory);
        }
    }
}
