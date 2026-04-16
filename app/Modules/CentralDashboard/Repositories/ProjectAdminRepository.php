<?php

namespace App\Modules\CentralDashboard\Repositories;

use App\Models\ProjectAdmin;

class ProjectAdminRepository
{
    public function paginate(int $perPage = 10)
    {
        return ProjectAdmin::latest()->paginate($perPage);
    }

    public function findById(int $id): ProjectAdmin
    {
        return ProjectAdmin::findOrFail($id);
    }

    public function create(array $data): ProjectAdmin
    {
        return ProjectAdmin::create($data);
    }
    public function update(ProjectAdmin $admin, array $data): ProjectAdmin
    {
        $admin->update($data);
        return $admin;
    }
    public function delete(int $id): void
    {
        ProjectAdmin::destroy($id);
    }
}