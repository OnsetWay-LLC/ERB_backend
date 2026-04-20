<?php

namespace App\Modules\CentralDashboard\Repositories;

use App\Models\License;

class LicenseRepository
{
    public function paginate(array $filters = [])
    {
        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = License::with([
            'licenseRequest',
            'generator',
            'installation',
        ])->latest();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->whereHas('licenseRequest', function ($sub) use ($search) {
                    $sub->where('owner_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                })->orWhereHas('installation', function ($sub) use ($search) {
                    $sub->where('device_name', 'like', "%{$search}%")
                        ->orWhere('master_device_name', 'like', "%{$search}%")
                        ->orWhere('installation_code', 'like', "%{$search}%")
                        ->orWhere('database_name', 'like', "%{$search}%")
                        ->orWhere('server_host', 'like', "%{$search}%");
                });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['license_type'])) {
            $query->where('license_type', $filters['license_type']);
        }

        if (!empty($filters['license_request_id'])) {
            $query->where('license_request_id', $filters['license_request_id']);
        }

        if (!empty($filters['client_installation_id'])) {
            $query->where('client_installation_id', $filters['client_installation_id']);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): License
    {
        return License::with([
            'licenseRequest',
            'generator',
            'logs.creator',
            'installation',
            'parentLicense',
        ])->findOrFail($id);
    }
}