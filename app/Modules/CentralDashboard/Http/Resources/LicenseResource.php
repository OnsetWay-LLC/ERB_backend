<?php

namespace App\Modules\CentralDashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'license_request_id' => $this->license_request_id,
            'activation_id'      => $this->activation_id,
            'duration_type'      => $this->duration_type,
            'starts_at'          => $this->starts_at,
            'expires_at'         => $this->expires_at,
            'sent_at'            => $this->sent_at,
            'activated_at'       => $this->activated_at,
            'status'             => $this->status,

            'generated_by' => $this->whenLoaded('generator', function () {
                return [
                    'id'    => $this->generator?->id,
                    'name'  => $this->generator?->name,
                    'email' => $this->generator?->email,
                ];
            }),

            'license_request' => $this->whenLoaded('licenseRequest', function () {
                return [
                    'id'         => $this->licenseRequest?->id,
                    'owner_name' => $this->licenseRequest?->owner_name,
                    'email'      => $this->licenseRequest?->email,
                    'status'     => $this->licenseRequest?->status,
                ];
            }),

            'logs' => $this->whenLoaded('logs', function () {
                return $this->logs->map(function ($log) {
                    return [
                        'id'         => $log->id,
                        'action'     => $log->action,
                        'note'       => $log->note,
                        'created_at' => $log->created_at,
                        'created_by' => [
                            'id'   => $log->creator?->id,
                            'name' => $log->creator?->name,
                        ],
                        'installation' => $this->whenLoaded('installation', function () {
    return [
        'id' => $this->installation?->id,
        'license_request_id' => $this->installation?->license_request_id,
        'database_name' => $this->installation?->database_name,
        'server_host' => $this->installation?->server_host,
        'master_device_name' => $this->installation?->master_device_name,
        'installation_status' => $this->installation?->installation_status,
    ];
}),
                    ];
                    
                });
            }),
        ];
    }
}