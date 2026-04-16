<?php

namespace App\Modules\CentralDashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientInstallationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'license_request_id' => $this->license_request_id,
            'database_name' => $this->database_name,
            'server_host' => $this->server_host,
            'server_port' => $this->server_port,
            'database_username' => $this->database_username,
            'backend_path' => $this->backend_path,
            'master_device_name' => $this->master_device_name,
            'installation_status' => $this->installation_status,
            'installed_at' => $this->installed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'license_request' => $this->whenLoaded('licenseRequest', function () {
                return [
                    'id' => $this->licenseRequest?->id,
                    'owner_name' => $this->licenseRequest?->owner_name,
                    'email' => $this->licenseRequest?->email,
                    'status' => $this->licenseRequest?->status,
                ];
            }),

            'active_license' => $this->whenLoaded('activeLicense', function () {
                return $this->activeLicense ? [
                    'id' => $this->activeLicense->id,
                    'license_type' => $this->activeLicense->license_type,
                    'duration_type' => $this->activeLicense->duration_type,
                    'status' => $this->activeLicense->status,
                    'starts_at' => $this->activeLicense->starts_at,
                    'expires_at' => $this->activeLicense->expires_at,
                ] : null;
            }),
        ];
    }
}