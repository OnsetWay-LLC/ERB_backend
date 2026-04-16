<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClientInstallation extends Model
{
    protected $fillable = [
        'license_request_id',
        'database_name',
        'server_host',
        'server_port',
        'database_username',
        'database_password',
        'backend_path',
        'master_device_name',
        'installation_status',
        'installed_at',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
    ];

    public function licenseRequest(): BelongsTo
    {
        return $this->belongsTo(LicenseRequest::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function activeLicense(): HasOne
    {
        return $this->hasOne(License::class)
            ->where('status', 'active')
            ->latestOfMany();
    }
}