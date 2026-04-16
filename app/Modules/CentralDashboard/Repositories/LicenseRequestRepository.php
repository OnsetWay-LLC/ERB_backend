<?php

namespace App\Modules\CentralDashboard\Repositories;

use App\Models\LicenseRequest;

class LicenseRequestRepository
{
    public function paginateWithLatestLicense(int $perPage = 10)
    {
        return LicenseRequest::with(['latestLicense', 'installation'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): LicenseRequest
    {
        return LicenseRequest::with(['licenses.generator', 'installation'])
            ->findOrFail($id);
    }
}