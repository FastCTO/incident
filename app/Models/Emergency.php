<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_type', 'description', 'reporting_phone', 
        'reporting_user_id', 'room_occupancy', 'school_occupancy'
    ];
}

