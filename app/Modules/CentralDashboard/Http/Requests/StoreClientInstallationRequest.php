<?php

namespace App\Modules\CentralDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientInstallationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_request_id' => ['required', 'exists:license_requests,id'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'server_host' => ['nullable', 'string', 'max:255'],
            'server_port' => ['nullable', 'integer', 'min:1'],
            'database_name' => ['nullable', 'string', 'max:255'],
          
            'installation_status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'database_created',
                    'initialized',
                    'failed',
                ]),
            ],

            'installed_at' => ['nullable', 'date'],
        ];
    }
}