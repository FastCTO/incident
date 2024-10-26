<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInfo extends Model
{
    use HasFactory;

    protected $table = 'school_infos';

    protected $fillable = [
        'name', 'address', 'latitude', 'longitude', 'total_floors',
        'max_capacity', 'student_hours_start', 'student_hours_end',
        'activity_hours_start', 'activity_hours_end',
        'cleaning_hours_start', 'cleaning_hours_end'
    ];
}

