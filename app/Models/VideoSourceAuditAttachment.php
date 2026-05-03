<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VideoSourceAuditAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_source_audit_id',
        'nvr_system_id',
        'organization_id',
        'site_id',
        'uploaded_by',
        'attachment_type',
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
        'sha256_hash',
        'notes',
        'ip_address',
        'user_agent',
        'request_method',
        'request_path',
    ];

    public function audit()
    {
        return $this->belongsTo(VideoSourceAudit::class, 'video_source_audit_id');
    }

    public function videoSource()
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

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getHumanFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getAttachmentTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->attachment_type ?? 'attachment'));
    }

    public function getUploaderDisplayNameAttribute(): string
    {
        if (!$this->uploader) {
            return 'System / Unknown user';
        }

        return $this->uploader->display_name
            ?? $this->uploader->name
            ?? $this->uploader->email
            ?? 'User #' . $this->uploaded_by;
    }
}
