<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'cell_number',
        'invited_by', 'token', 'expires_at',
    ];
}

