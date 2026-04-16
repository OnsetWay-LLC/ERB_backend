<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LicenseRequest extends Model
{
    protected $fillable = [
        'company_name',
        'owner_name',
        'username',
        'email',
        'password',
        'email_verified_at',
        'status',
        'requested_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'requested_at' => 'datetime',
    ];

    public function installation(): HasOne
    {
        return $this->hasOne(ClientInstallation::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function latestLicense(): HasOne
    {
        return $this->hasOne(License::class)->latestOfMany();
    }
}