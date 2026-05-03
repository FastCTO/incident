<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_organization_id',
        'name',
        'organization_type',
        'status',
        'contact_name',
        'contact_email',
        'contact_phone',
        'website',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'logo_path',
        'notes',
    ];

    public function parentOrganization()
    {
        return $this->belongsTo(Organization::class, 'parent_organization_id');
    }

    public function childOrganizations()
    {
        return $this->hasMany(Organization::class, 'parent_organization_id')->orderBy('name');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function nvrSystems()
    {
        return $this->hasMany(NvrSystem::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: 'Organization #' . $this->id;
    }

    public function getTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->organization_type ?? 'customer'));
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status ?? 'active');
    }
}
