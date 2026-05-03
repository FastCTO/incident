<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSourceAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nvr_system_id',
        'organization_id',
        'site_id',
        'performed_by',
        'audit_type',
        'audit_status',
        'performed_at',
        'ip_address',
        'user_agent',
        'request_method',
        'request_path',
        'serial_number_observed',
        'manufacturer_observed',
        'model_observed',
        'firmware_version',
        'software_version',
        'os_version',
        'hostname_observed',
        'source_ip_observed',
        'mac_address_observed',
        'system_datetime',
        'system_time_zone',
        'time_drift_notes',
        'total_storage',
        'used_storage',
        'available_storage',
        'storage_health',
        'oldest_recording_at',
        'estimated_retention_days',
        'recording_mode',
        'total_camera_count',
        'active_camera_count',
        'offline_camera_count',
        'disabled_camera_count',
        'camera_view_notes',
        'admin_user_count',
        'standard_user_count',
        'unknown_user_count',
        'last_login_notes',
        'failed_login_notes',
        'unusual_activity_notes',
        'security_notes',
        'retention_notes',
        'overall_notes',
        'recommended_actions',
        'next_audit_due_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'system_datetime' => 'datetime',
        'oldest_recording_at' => 'datetime',
        'next_audit_due_at' => 'datetime',
        'estimated_retention_days' => 'integer',
        'total_camera_count' => 'integer',
        'active_camera_count' => 'integer',
        'offline_camera_count' => 'integer',
        'disabled_camera_count' => 'integer',
        'admin_user_count' => 'integer',
        'standard_user_count' => 'integer',
        'unknown_user_count' => 'integer',
    ];

    public function videoSource()
    {
        return $this->belongsTo(NvrSystem::class, 'nvr_system_id');
    }

    public function nvrSystem()
    {
        return $this->belongsTo(NvrSystem::class, 'nvr_system_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function getAuditTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->audit_type ?? 'audit'));
    }

    public function getAuditStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->audit_status ?? 'unknown'));
    }

    public function getPerformerDisplayNameAttribute(): string
    {
        if (!$this->performer) {
            return 'System / Unknown user';
        }

        return $this->performer->display_name
            ?? $this->performer->name
            ?? $this->performer->email
            ?? 'User #' . $this->performed_by;
    }
}
