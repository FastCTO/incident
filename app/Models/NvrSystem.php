<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NvrSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'site_id',
        'name',
        'system_type',
        'status',
        'manufacturer',
        'model',
        'serial_number',
        'hostname',
        'ip_address',
        'local_url',
        'remote_url',
        'camera_count',
        'estimated_retention_days',
        'storage_notes',
        'access_notes',
        'last_checked_at',
        'notes',
    ];

    protected $casts = [
        'camera_count' => 'integer',
        'estimated_retention_days' => 'integer',
        'last_checked_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function audits()
    {
        return $this->hasMany(VideoSourceAudit::class, 'nvr_system_id')->latest('performed_at')->latest();
    }

    public function latestAudit()
    {
        return $this->hasOne(VideoSourceAudit::class, 'nvr_system_id')->latestOfMany('performed_at');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'Video Source #' . $this->id;
    }

    public function getSystemLabelAttribute(): string
    {
        $parts = array_filter([
            $this->manufacturer,
            $this->model,
            $this->system_type ? strtoupper(str_replace('_', ' ', $this->system_type)) : null,
        ]);

        return $parts ? implode(' / ', $parts) : '-';
    }
}
