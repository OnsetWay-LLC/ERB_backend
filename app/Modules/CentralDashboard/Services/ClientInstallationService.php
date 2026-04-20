<?php

namespace App\Modules\CentralDashboard\Services;

use App\Models\ClientInstallation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientInstallationService
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 10;

        $query = ClientInstallation::query()->latest();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                  ->orWhere('server_host', 'like', "%{$search}%")
                  ->orWhere('database_name', 'like', "%{$search}%")
                 ;
            });
        }

       

        if (!empty($filters['installation_status'])) {
            $query->where('installation_status', $filters['installation_status']);
        }

        if (!empty($filters['license_request_id'])) {
            $query->where('license_request_id', $filters['license_request_id']);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): ClientInstallation
    {
        $data['installation_status'] = $data['installation_status'] ?? 'pending';

        return ClientInstallation::create($data);
    }

    public function getById(int $id): ClientInstallation
    {
        return ClientInstallation::findOrFail($id);
    }

    public function update(int $id, array $data): ClientInstallation
    {
        $installation = $this->getById($id);
        $installation->update($data);

        return $installation->refresh();
    }
}