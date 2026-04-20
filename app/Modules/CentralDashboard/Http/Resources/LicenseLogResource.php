<?php

namespace App\Modules\CentralDashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenseLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'license_id' => $this->license_id,
            'action' => $this->action,
            'note' => $this->note,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),

            'creator' => [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
                'email' => $this->creator?->email,
            ],

            'license' => $this->whenLoaded('license', function () {
                return [
                    'id' => $this->license?->id,
                    'license_type' => $this->license?->license_type,
                    'status' => $this->license?->status,
                    'duration_type' => $this->license?->duration_type,
                    'starts_at' => $this->license?->starts_at,
                    'expires_at' => $this->license?->expires_at,
                    'license_request' => [
                        'id' => $this->license?->licenseRequest?->id,
                        'owner_name' => $this->license?->licenseRequest?->owner_name,
                        'email' => $this->license?->licenseRequest?->email,
                    ],
                    'installation' => [
                        'id' => $this->license?->installation?->id,
                        'device_type' => $this->license?->installation?->device_type,
                        'device_name' => $this->license?->installation?->device_name,
                        'master_device_name' => $this->license?->installation?->master_device_name,
                        'installation_code' => $this->license?->installation?->installation_code,
                    ],
                ];
            }),
        ];
    }
}