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
        'device_type',
        'device_name',
        'master_device_name',
        'server_host',
        'server_port',
        'database_name',
        'backend_path',
        'installation_code',
        'installation_status',
        'installed_at',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
        'server_port' => 'integer',
    ];

    public function licenseRequest(): BelongsTo
    {
        return $this->belongsTo(LicenseRequest::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class, 'client_installation_id');
    }

    public function activeLicense(): HasOne
    {
        return $this->hasOne(License::class, 'client_installation_id')
            ->where('status', 'active')
            ->latestOfMany();
    }
}