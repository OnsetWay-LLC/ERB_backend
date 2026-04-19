<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class License extends Model
{
    protected $fillable = [
        'license_request_id',
        'client_installation_id',
        'license_type',
        'parent_license_id',
        'activation_token_hash',
        'activation_token_hint',
        'duration_type',
        'starts_at',
        'expires_at',
        'sent_at',
        'activated_at',
        'token_expires_at',
        'used_at',
        'renewal_reminder_sent_at',
        'status',
        'generated_by',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'sent_at' => 'datetime',
        'activated_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'used_at' => 'datetime',
        'renewal_reminder_sent_at' => 'datetime',
    ];

    public function licenseRequest(): BelongsTo
    {
        return $this->belongsTo(LicenseRequest::class);
    }

    public function clientInstallation(): BelongsTo
    {
        return $this->belongsTo(ClientInstallation::class, 'client_installation_id');
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(ProjectAdmin::class, 'generated_by');
    }

    public function parentLicense(): BelongsTo
    {
        return $this->belongsTo(License::class, 'parent_license_id');
    }

    public function renewals(): HasMany
    {
        return $this->hasMany(License::class, 'parent_license_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LicenseLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeGenerated($query)
    {
        return $query->where('status', 'generated');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }
}