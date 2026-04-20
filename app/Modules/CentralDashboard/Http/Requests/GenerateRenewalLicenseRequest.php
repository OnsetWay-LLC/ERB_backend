<?php

namespace App\Modules\CentralDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateRenewalLicenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_request_id' => ['required', 'integer', 'exists:license_requests,id'],
            'client_installation_id' => ['required', 'integer', 'exists:client_installations,id'],
            'duration_type' => ['required', 'in:fourteen_days,one_year'],
            'generated_by' => ['nullable', 'integer', 'exists:project_admins,id'],
        ];
    }
}