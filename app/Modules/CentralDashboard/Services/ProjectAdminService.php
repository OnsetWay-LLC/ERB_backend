<?php

namespace App\Modules\CentralDashboard\Services;

use App\Models\ProjectAdmin;

class ProjectAdminService
{
    public function create(array $data): ProjectAdmin
    {
        return ProjectAdmin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role'],
            'is_active' => $data['is_active'],
        ]);
    }
    public function update(ProjectAdmin $admin, array $data): ProjectAdmin
    {
        $admin->update([
            'name' => $data['name'] ?? $admin->name,
            'email' => $data['email'] ?? $admin->email,
            'password' => isset($data['password']) ? bcrypt($data['password']) : $admin->password,
            'role' => $data['role'] ?? $admin->role,
            'is_active' => $data['is_active'] ?? $admin->is_active,
        ]);

        return $admin;
    }

    public function delete(int $id): void
    {
        ProjectAdmin::destroy($id);
    }
}