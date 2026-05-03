<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSourceAuditAddendum extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_source_audit_id',
        'nvr_system_id',
        'organization_id',
        'site_id',
        'added_by',
        'addendum_type',
        'body',
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

    public function author()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getAddendumTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->addendum_type ?? 'note'));
    }

    public function getAuthorDisplayNameAttribute(): string
    {
        if (!$this->author) {
            return 'System / Unknown user';
        }

        return $this->author->display_name
            ?? $this->author->name
            ?? $this->author->email
            ?? 'User #' . $this->added_by;
    }
}
