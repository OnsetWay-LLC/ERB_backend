<?php

namespace App\Modules\CentralDashboard\Services;

use App\Models\LicenseLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LicenseLogService
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $perPage = (int) ($filters['per_page'] ?? 10);

        $query = LicenseLog::with(['license.licenseRequest', 'creator'])
            ->latest('created_at');

        if (!empty($filters['license_id'])) {
            $query->where('license_id', $filters['license_id']);
        }

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        return $query->paginate($perPage);
    }

    public function getById(int $id): LicenseLog
    {
        return LicenseLog::with([
            'license.licenseRequest',
            'license.installation',
            'creator',
        ])->findOrFail($id);
    }
}