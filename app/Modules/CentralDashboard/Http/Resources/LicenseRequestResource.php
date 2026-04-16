<?php

namespace App\Modules\CentralDashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LicenseRequestResource extends JsonResource
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
            'latest_license'    => $this->whenLoaded('latestLicense', function () {
                return [
                    'id'            => $this->latestLicense?->id,
                    'activation_id' => $this->latestLicense?->activation_id,
                    'duration_type' => $this->latestLicense?->duration_type,
                    'status'        => $this->latestLicense?->status,
                    'expires_at'    => $this->latestLicense?->expires_at,
                ];
            }),
        ];
    }
}