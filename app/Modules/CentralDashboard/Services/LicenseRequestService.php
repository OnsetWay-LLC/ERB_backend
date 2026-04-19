<?php

namespace App\Modules\CentralDashboard\Services;

use App\Models\LicenseRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LicenseRequestService
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $perPage = $filters['per_page'] ?? 10;

        $query = LicenseRequest::query()->latest();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function create(array $data): LicenseRequest
    {
        $data['status'] = $data['status'] ?? 'pending';
        $data['requested_at'] = $data['requested_at'] ?? now();

        return LicenseRequest::create($data);
    }

    public function getById(int $id): LicenseRequest
    {
        return LicenseRequest::findOrFail($id);
    }

    public function update(int $id, array $data): LicenseRequest
    {
        $licenseRequest = $this->getById($id);
        $licenseRequest->update($data);

        return $licenseRequest->refresh();
    }

    public function delete(int $id): void
    {
        $licenseRequest = $this->getById($id);
        $licenseRequest->delete();
    }
}