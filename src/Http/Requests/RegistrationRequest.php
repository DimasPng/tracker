<?php

namespace App\Http\Requests;

use App\Contract\ResponseFactoryInterface;
use App\Core\FormRequest\AbstractFormRequest;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repository\UserRepository;

class RegistrationRequest extends AbstractFormRequest
{
    private UserRepository $userRepository;

    public function __construct(
        Request $request,
        ResponseFactoryInterface $responseFactory,
        UserRepository $userRepository
    ) {
        parent::__construct($request, $responseFactory);
        $this->userRepository = $userRepository;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
            'password_confirmation' => ['required', 'confirmed:password'],
            'terms' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please provide an email address.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.confirmed' => 'Passwords do not match.',
            'terms.required' => 'You must accept the Terms and Conditions.',
        ];
    }

    public function validate(): bool
    {
        $isValid = parent::validate();

        if ($isValid && !empty($this->validatedData['email'])) {
            if ($this->userRepository->existsByEmail($this->validatedData['email'])) {
                $this->errors[] = $this->messages()['email.unique'] ?? 'This email address is already in use.';
                $isValid = false;
            }
        }

        return $isValid;
    }

    public function failedValidationResponse(): Response
    {
        $content = View::render('auth.register', [
            'title' => 'Register - Buy A Cow',
            'error' => $this->getErrorsAsString(),
            'email' => $this->post('email'),
            'hideNavbar' => true,
            'hideFooter' => true,
            'mainClass' => 'flex min-h-full',
        ]);

        return $this->getResponseFactory()->html($content);
    }
}
