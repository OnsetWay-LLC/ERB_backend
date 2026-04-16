<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'license_id',
        'action',
        'note',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(ProjectAdmin::class, 'created_by');
    }
}