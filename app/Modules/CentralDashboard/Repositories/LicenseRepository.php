<?php

namespace App\Modules\CentralDashboard\Repositories;

use App\Models\License;

class LicenseRepository
{
    public function paginate(int $perPage = 10)
    {
        return License::with(['licenseRequest', 'generator'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): License
    {
        return License::with(['licenseRequest', 'generator', 'logs.creator', 'installation'])
            ->findOrFail($id);
    }
}