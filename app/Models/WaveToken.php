<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaveToken extends Model
{
    use HasFactory;

    protected $table = 'wave_tokens';

    protected $fillable = [
        'user_id',
        'auth_token',
        'stream_url_hd',
        'stream_url_sd',
        'auth_token_expires_at',
        'stream_url_expires_at'
    ];

    protected $casts = [
        'auth_token_expires_at' => 'datetime',
        'stream_url_expires_at' => 'datetime'
    ];
}

