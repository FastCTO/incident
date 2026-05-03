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
        'installation_date',

        'system_datetime',
        'system_time_zone',
        'ntp_enabled',
        'time_drift_notes',

        'total_storage',
        'total_storage_amount',
        'total_storage_unit',
        'used_storage',
        'available_storage',
        'storage_health',
        'storage_status',

        'oldest_recording_at',
        'oldest_recording_verified',
        'estimated_retention_days',
        'recording_mode',
        'export_test_performed',
        'export_test_status',
        'export_test_notes',

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

        'logs_reviewed',
        'log_review_window',
        'log_notes',

        'retention_notes',
        'overall_notes',
        'recommended_actions',
        'next_audit_due_at',

        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'installation_date' => 'date',
        'system_datetime' => 'datetime',
        'ntp_enabled' => 'boolean',
        'oldest_recording_at' => 'datetime',
        'oldest_recording_verified' => 'boolean',
        'export_test_performed' => 'boolean',
        'logs_reviewed' => 'boolean',
        'next_audit_due_at' => 'datetime',
        'locked_at' => 'datetime',
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

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function attachments()
    {
        return $this->hasMany(VideoSourceAuditAttachment::class, 'video_source_audit_id')->latest();
    }

    public function addendums()
    {
        return $this->hasMany(VideoSourceAuditAddendum::class, 'video_source_audit_id')->latest();
    }

    public function isLocked(): bool
    {
        return !is_null($this->locked_at);
    }

    public function isDraft(): bool
    {
        return is_null($this->locked_at);
    }

    public function getAuditTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->audit_type ?? 'audit'));
    }

    public function getAuditStatusLabelAttribute(): string
    {
        if ($this->isDraft()) {
            return 'Draft';
        }

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

    public function getLockedByDisplayNameAttribute(): string
    {
        if (!$this->lockedBy) {
            return '-';
        }

        return $this->lockedBy->display_name
            ?? $this->lockedBy->name
            ?? $this->lockedBy->email
            ?? 'User #' . $this->locked_by;
    }

    public function getNtpEnabledLabelAttribute(): string
    {
        if ($this->ntp_enabled === true) {
            return 'Yes';
        }

        if ($this->ntp_enabled === false) {
            return 'No';
        }

        return 'Unknown / Not visible';
    }

    public function getOldestRecordingVerifiedLabelAttribute(): string
    {
        if ($this->oldest_recording_verified === true) {
            return 'Yes';
        }

        if ($this->oldest_recording_verified === false) {
            return 'No';
        }

        return 'Unknown / Not checked';
    }

    public function getExportTestPerformedLabelAttribute(): string
    {
        if ($this->export_test_performed === true) {
            return 'Yes';
        }

        if ($this->export_test_performed === false) {
            return 'No';
        }

        return 'Unknown / Not checked';
    }

    public function getLogsReviewedLabelAttribute(): string
    {
        if ($this->logs_reviewed === true) {
            return 'Yes';
        }

        if ($this->logs_reviewed === false) {
            return 'No';
        }

        return 'Unknown / Not checked';
    }
}
