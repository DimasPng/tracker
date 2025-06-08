<?php

namespace App\Core\FormRequest;

use App\Contract\ResponseFactoryInterface;
use App\Core\Request;
use App\Core\Response;

abstract class AbstractFormRequest
{
    protected array $validatedData = [];

    protected array $errors = [];

    private ResponseFactoryInterface $responseFactory;

    private Request $request;

    public function __construct(
        Request $request,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->request = $request;
        $this->responseFactory = $responseFactory;
    }

    abstract public function rules(): array;

    public function messages(): array
    {
        return [];
    }

    public function validate(): bool
    {
        $rules = $this->rules();
        $messages = $this->messages();
        $this->errors = [];
        $this->validatedData = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $this->post($field);

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $this->errors[] = $messages[$field . '.required'] ?? "The {$field} field is required.";

                    break;
                } elseif ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = $messages[$field . '.email'] ?? "The {$field} field must be a valid email address.";

                    break;
                }
            }

            if (!empty($value)) {
                $this->validatedData[$field] = $value;
            }
        }

        return empty($this->errors);
    }

    public function validated(): array
    {
        return $this->validatedData;
    }

    public function getErrorsAsString(): string
    {
        return implode(' ', $this->errors);
    }

    public function failedValidationResponse(): Response
    {
        return $this->responseFactory->error('Validation failed');
    }

    public function post(string $key, ?string $default = null): ?string
    {
        return $this->request->post($key, $default);
    }

    public function isPost(): bool
    {
        return $this->request->isPost();
    }

    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->responseFactory;
    }
}
