<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class ProjectAdmin extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'guard' => 'project_admin',
            'role' => $this->role,
            'can_access_dashboard' => $this->is_active,
        ];
    }

    public function generatedLicenses(): HasMany
    {
        return $this->hasMany(License::class, 'generated_by');
    }

    public function licenseLogs(): HasMany
    {
        return $this->hasMany(LicenseLog::class, 'created_by');
    }
}