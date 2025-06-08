<?php

namespace App\Contract;

use App\Core\FormRequest\AbstractFormRequest;
use App\Core\Request;

interface FormRequestFactoryInterface
{
    public function make(string $formRequestClass, Request $request): AbstractFormRequest;
}
