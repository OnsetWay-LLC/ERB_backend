<?php

namespace App\Modules\CentralDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyActivationTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:license_requests,email'],
            'activation_token' => ['required', 'string', 'size:7'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email does not exist in license requests.',

            'activation_token.required' => 'Activation token is required.',
            'activation_token.string' => 'Activation token must be a string.',
            'activation_token.size' => 'Activation token must be exactly 7 characters.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim((string) $this->email)),
            'activation_token' => strtoupper(trim((string) $this->activation_token)),
        ]);
    }
}