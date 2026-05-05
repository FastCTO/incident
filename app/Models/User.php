<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'cell_phone',
        'organization_name',
        'role_title',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getDisplayNameAttribute(): string
    {
        $fullName = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));

        if ($fullName !== '') {
            return $fullName;
        }

        return $this->name
            ?? $this->email
            ?? 'User #' . $this->id;
    }

    public function getProfileLabelAttribute(): string
    {
        $parts = [];

        $parts[] = $this->display_name;

        if ($this->role_title) {
            $parts[] = $this->role_title;
        }

        if ($this->organization_name) {
            $parts[] = $this->organization_name;
        }

        return implode(' - ', array_filter($parts));
    }
}
