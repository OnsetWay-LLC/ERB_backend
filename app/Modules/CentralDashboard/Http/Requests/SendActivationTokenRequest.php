<?php

namespace App\Modules\CentralDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendActivationTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_id' => ['required', 'integer', 'exists:licenses,id'],
            'activation_token' => ['required', 'string', 'size:7'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activation_token' => strtoupper(trim((string) $this->activation_token)),
        ]);
    }
}