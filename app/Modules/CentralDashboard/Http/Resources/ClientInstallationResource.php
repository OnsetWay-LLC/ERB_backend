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

          
            'device_name' => $this->device_name,
          

            'server_host' => $this->server_host,
            'server_port' => $this->server_port,
            'database_name' => $this->database_name,
         

            'installation_status' => $this->installation_status,
            'installed_at' => $this->installed_at?->format('Y-m-d H:i:s'),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}