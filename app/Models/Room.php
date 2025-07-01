<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(RoomFacility::class);
    }

    public function room_facilities()
    {
        return $this->belongsToMany(RoomFacility::class, 'room_room_facility');
    }
}
