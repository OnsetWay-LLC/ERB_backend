<?php

namespace App\Modules\CentralDashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\CentralDashboard\Http\Resources\LicenseResource;

class LicenseRequestDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'company_name'      => $this->company_name,
            'owner_name'        => $this->owner_name,
            'username'          => $this->username,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'status'            => $this->status,
            'requested_at'      => $this->requested_at,

            'installation' => $this->whenLoaded('installation', function () {
                return [
                    'id'                  => $this->installation?->id,
                    'database_name'       => $this->installation?->database_name,
                    'server_host'         => $this->installation?->server_host,
                    'server_port'         => $this->installation?->server_port,
                    'backend_path'        => $this->installation?->backend_path,
                    'master_device_name'  => $this->installation?->master_device_name,
                    'installation_status' => $this->installation?->installation_status,
                    'installed_at'        => $this->installation?->installed_at,
                ];
            }),

            'licenses' => LicenseResource::collection($this->whenLoaded('licenses')),
        ];
    }
}