<?php

namespace App\Modules\CentralDashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'license_request_id' => $this->license_request_id,
            'client_installation_id' => $this->client_installation_id,
            'parent_license_id' => $this->parent_license_id,
            'license_type' => $this->license_type,
            'activation_token_hint' => $this->activation_token_hint,
            'duration_type' => $this->duration_type,
            'token_expires_at' => $this->token_expires_at,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at,
            'sent_at' => $this->sent_at,
            'activated_at' => $this->activated_at,
            'used_at' => $this->used_at,
            'status' => $this->status,

            'generated_by' => $this->whenLoaded('generator', function () {
                return [
                    'id' => $this->generator?->id,
                    'name' => $this->generator?->name,
                    'email' => $this->generator?->email,
                ];
            }),

            'license_request' => $this->whenLoaded('licenseRequest', function () {
                return [
                    'id' => $this->licenseRequest?->id,
                    'owner_name' => $this->licenseRequest?->owner_name,
                    'email' => $this->licenseRequest?->email,
                    'status' => $this->licenseRequest?->status,
                ];
            }),

            'installation' => $this->whenLoaded('installation', function () {
                return [
                    'id' => $this->installation?->id,
                    'license_request_id' => $this->installation?->license_request_id,
                    'device_type' => $this->installation?->device_type,
                    'device_name' => $this->installation?->device_name,
                    'database_name' => $this->installation?->database_name,
                    'server_host' => $this->installation?->server_host,
                    'server_port' => $this->installation?->server_port,
                    'master_device_name' => $this->installation?->master_device_name,
                    'installation_code' => $this->installation?->installation_code,
                    'installation_status' => $this->installation?->installation_status,
                ];
            }),

            'logs' => $this->whenLoaded('logs', function () {
                return $this->logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'note' => $log->note,
                        'created_at' => $log->created_at,
                        'created_by' => [
                            'id' => $log->creator?->id,
                            'name' => $log->creator?->name,
                        ],
                    ];
                });
            }),
        ];
    }
}