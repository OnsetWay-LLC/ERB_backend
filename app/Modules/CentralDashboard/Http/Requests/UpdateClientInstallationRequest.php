<?php

namespace App\Modules\CentralDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientInstallationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'database_name' => ['nullable', 'string', 'max:255'],
            'server_host' => ['nullable', 'string', 'max:255'],
            'server_port' => ['nullable', 'string', 'max:50'],
            'database_username' => ['nullable', 'string', 'max:255'],
            'database_password' => ['nullable', 'string'],
            'backend_path' => ['nullable', 'string', 'max:500'],
            'master_device_name' => ['nullable', 'string', 'max:255'],
            'installation_status' => ['nullable', 'in:pending,database_created,initialized,failed,disabled'],
            'installed_at' => ['nullable', 'date'],
        ];
    }
}