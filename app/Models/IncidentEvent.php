<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'user_id',
        'event_type',
        'description',
        'ip_address',
        'user_agent',
        'request_method',
        'request_path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActorDisplayNameAttribute(): string
    {
        if (!$this->actor) {
            return 'System / Unknown user';
        }

        return $this->actor->display_name
            ?? $this->actor->name
            ?? $this->actor->email
            ?? 'User #' . $this->user_id;
    }
}
