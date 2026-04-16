<?php

namespace App\Modules\CentralDashboard\Repositories;

use App\Models\ClientInstallation;

class ClientInstallationRepository
{
    public function paginate(int $perPage = 10)
    {
        return ClientInstallation::with(['licenseRequest', 'activeLicense'])
            ->latest()
            ->paginate($perPage);
    }

    public function findById(int $id): ClientInstallation
    {
        return ClientInstallation::with(['licenseRequest', 'licenses', 'activeLicense'])
            ->findOrFail($id);
    }

    public function create(array $data): ClientInstallation
    {
        return ClientInstallation::create($data);
    }

    public function update(ClientInstallation $installation, array $data): ClientInstallation
    {
        $installation->update($data);

        return $installation->fresh(['licenseRequest', 'activeLicense']);
    }
}