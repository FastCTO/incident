<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'site_type',
        'status',
        'contact_name',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'time_zone',
        'notes',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function nvrSystems()
    {
        return $this->hasMany(NvrSystem::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'Site #' . $this->id;
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return $parts ? implode(', ', $parts) : '-';
    }
}
