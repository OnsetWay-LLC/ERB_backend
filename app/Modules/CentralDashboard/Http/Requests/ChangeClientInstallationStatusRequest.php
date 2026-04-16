<?php

namespace App\Modules\CentralDashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeClientInstallationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'installation_status' => ['required', 'in:pending,database_created,initialized,failed,disabled'],
        ];
    }
}