<?php

namespace App\Core\Validator;

abstract class AbstractValidator
{
    protected function applyRule(string $field, $value, string $rule, array $allData): ?string
    {
        switch ($rule) {
            case 'required':
                return empty($value) ? $this->getErrorMessage($field, 'required') : null;

            case 'email':
                return (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    ? $this->getErrorMessage($field, 'email') : null;

            default:
                if (str_contains($rule, ':')) {
                    [$ruleName, $parameter] = explode(':', $rule, 2);

                    return $this->applyParameterizedRule($field, $value, $ruleName, $parameter, $allData);
                }

                return null;
        }
    }

    protected function applyParameterizedRule(string $field, $value, string $ruleName, string $parameter, array $allData): ?string
    {
        switch ($ruleName) {
            case 'min':
                $minLength = (int) $parameter;

                return (!empty($value) && strlen($value) < $minLength)
                    ? $this->getErrorMessage($field, 'min', ['min' => $minLength]) : null;

            case 'confirmed':
                $confirmationField = $parameter;
                $confirmationValue = $allData[$confirmationField] ?? null;

                return ($value !== $confirmationValue)
                    ? $this->getErrorMessage($field, 'confirmed') : null;

            default:
                return null;
        }
    }

    protected function getErrorMessage(string $field, string $rule, array $parameters = []): string
    {
        $messages = [
            'required' => 'The :field field is required.',
            'email' => 'Please provide a valid email address.',
            'min' => 'The :field must be at least :min characters long.',
            'confirmed' => 'The :field confirmation does not match.',
        ];

        $message = $messages[$rule] ?? 'The :field field is invalid.';

        $message = str_replace(':field', $field, $message);

        foreach ($parameters as $key => $value) {
            $message = str_replace(":{$key}", $value, $message);
        }

        return $message;
    }

    abstract public function rules(): array;
}
