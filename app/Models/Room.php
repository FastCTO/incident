<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_full_name',
        'room_number',
        'max_capacity',
        'occpd_normal',
        'last_occ_update',
        'evac_zone',
        'room_status',
        'room_type',
        'comment',
        'floor_number',
        'current_occupancy',
        'status'
    ];

    /**
     * A room can have many users (teachers or otherwise) assigned via their home_room column.
     * home_room on users points to room_number on rooms.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'home_room', 'room_number');
    }
}

