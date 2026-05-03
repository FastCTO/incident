<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'incident_type',
        'status',
        'location_name',
        'address',
        'incident_datetime',
        'summary',
        'notes',
        'created_by',
        'archived_at',
        'archived_by',
    ];

    protected $casts = [
        'incident_datetime' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function files()
    {
        return $this->hasMany(IncidentFile::class)->latest();
    }

    public function events()
    {
        return $this->hasMany(IncidentEvent::class)->latest();
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    public function getIsArchivedAttribute(): bool
    {
        return !is_null($this->archived_at);
    }
}
