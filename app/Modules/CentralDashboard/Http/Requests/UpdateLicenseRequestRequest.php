<?php

namespace App\Modules\CentralDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLicenseRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $licenseRequestId = $this->route('id');

        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'owner_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('license_requests', 'email')->ignore($licenseRequestId),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'email_verified',
                    'activation_sent',
                    'activated',
                    'expired',
                    'blocked',
                ]),
            ],
            'notes' => ['nullable', 'string'],
            'email_verified_at' => ['nullable', 'date'],
            'requested_at' => ['nullable', 'date'],
        ];
    }
}