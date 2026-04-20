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
            'client_installation_id' => ['required', 'integer', 'exists:client_installations,id'],
            'activation_token' => ['required', 'string', 'size:7'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'This email does not exist in license requests.',

            'client_installation_id.required' => 'Client installation id is required.',
            'client_installation_id.integer' => 'Client installation id must be an integer.',
            'client_installation_id.exists' => 'This client installation does not exist.',

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