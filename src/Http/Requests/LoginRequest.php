<?php

namespace App\Http\Requests;

use App\Core\FormRequest\AbstractFormRequest;
use App\Core\Response;
use App\Core\View;

class LoginRequest extends AbstractFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please provide an email address.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'Password is required.',
        ];
    }

    public function failedValidationResponse(): Response
    {
        $content = View::render('auth.login', [
            'title' => 'Sign In - Buy A Cow',
            'error' => $this->getErrorsAsString(),
            'email' => $this->post('email'),
            'hideNavbar' => true,
            'hideFooter' => true,
            'mainClass' => 'flex min-h-full',
        ]);

        return $this->getResponseFactory()->html($content);
    }
}
