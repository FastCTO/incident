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
    ];

    protected $casts = [
        'incident_datetime' => 'datetime',
    ];

    public function files()
    {
        return $this->hasMany(IncidentFile::class)->latest();
    }

    public function events()
    {
        return $this->hasMany(IncidentEvent::class)->latest();
    }
}
