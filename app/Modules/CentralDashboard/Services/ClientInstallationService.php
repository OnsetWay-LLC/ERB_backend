<?php

namespace App\Modules\CentralDashboard\Services;

use App\Models\ClientInstallation;
use Illuminate\Support\Facades\Crypt;

class ClientInstallationService
{
    public function create(array $data): ClientInstallation
    {
        return ClientInstallation::create([
            'license_request_id' => $data['license_request_id'],
            'database_name' => $data['database_name'] ?? null,
            'server_host' => $data['server_host'] ?? null,
            'server_port' => $data['server_port'] ?? null,
            'database_username' => $data['database_username'] ?? null,
            'database_password' => isset($data['database_password'])
                ? Crypt::encryptString($data['database_password'])
                : null,
            'backend_path' => $data['backend_path'] ?? null,
            'master_device_name' => $data['master_device_name'] ?? null,
            'installation_status' => $data['installation_status'] ?? 'pending',
            'installed_at' => $data['installed_at'] ?? null,
        ]);
    }

    public function update(ClientInstallation $installation, array $data): ClientInstallation
    {
        $payload = [
            'database_name' => $data['database_name'] ?? $installation->database_name,
            'server_host' => $data['server_host'] ?? $installation->server_host,
            'server_port' => $data['server_port'] ?? $installation->server_port,
            'database_username' => $data['database_username'] ?? $installation->database_username,
            'backend_path' => $data['backend_path'] ?? $installation->backend_path,
            'master_device_name' => $data['master_device_name'] ?? $installation->master_device_name,
            'installation_status' => $data['installation_status'] ?? $installation->installation_status,
            'installed_at' => $data['installed_at'] ?? $installation->installed_at,
        ];

        if (array_key_exists('database_password', $data) && ! empty($data['database_password'])) {
            $payload['database_password'] = Crypt::encryptString($data['database_password']);
        }

        $installation->update($payload);

        return $installation->fresh(['licenseRequest', 'activeLicense']);
    }

   
}